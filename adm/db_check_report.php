<?php
include_once('./_common.php');

$target_types = array('Awards', 'Work', 'Finance', 'Report');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>DB Check - T_BOARD report</title>
<style>
  body { font-family: monospace; padding: 30px; background: #f5f5f5; }
  h2 { color: #333; border-bottom: 2px solid #333; padding-bottom: 6px; }
  h3 { color: #555; margin-top: 30px; }
  table { border-collapse: collapse; background: #fff; min-width: 400px; }
  th { background: #333; color: #fff; padding: 8px 14px; text-align: left; }
  td { padding: 7px 14px; border-bottom: 1px solid #ddd; }
  .ok   { color: #2a7a2a; font-weight: bold; }
  .warn { color: #b85c00; font-weight: bold; }
  .err  { color: #cc0000; font-weight: bold; }
  .section { background: #fff; border: 1px solid #ccc; padding: 16px 20px; margin-bottom: 20px; border-radius: 4px; }
  .label { color: #888; font-size: 12px; margin-bottom: 4px; }
</style>
</head>
<body>
<h2>DB Check &mdash; T_BOARD (B_CODE = 'report')</h2>
<p style="color:#888">실행시각: <?php echo date('Y-m-d H:i:s'); ?></p>

<?php
// -------------------------------------------------------
// 1. T_BOARD_CONFIG 현재 상태
// -------------------------------------------------------
?>
<div class="section">
  <h3>1. T_BOARD_CONFIG 현재 상태</h3>
  <?php
  $config = sql_fetch(" SELECT BC_CODE, BC_NAME FROM T_BOARD_CONFIG WHERE BC_CODE = 'report' ");
  if ($config) {
      $name_ok = ($config['BC_NAME'] === 'News');
      echo "<table><tr><th>BC_CODE</th><th>BC_NAME</th><th>상태</th></tr>";
      echo "<tr>";
      echo "<td>" . htmlspecialchars($config['BC_CODE']) . "</td>";
      echo "<td>" . htmlspecialchars($config['BC_NAME']) . "</td>";
      echo "<td class='" . ($name_ok ? 'ok' : 'warn') . "'>";
      echo $name_ok ? "&#10003; 이미 'News' 로 변경됨" : "&#9888; 아직 변경 전 (db_update 미실행)";
      echo "</td></tr></table>";
  } else {
      echo "<p class='err'>&#10007; BC_CODE = 'report' 레코드 없음</p>";
  }
  ?>
</div>

<?php
// -------------------------------------------------------
// 2. B_TYPE 분포
// -------------------------------------------------------
?>
<div class="section">
  <h3>2. T_BOARD B_TYPE 분포 (B_CODE = 'report')</h3>
  <?php
  $result = sql_query(" SELECT B_TYPE, COUNT(*) AS CNT FROM T_BOARD WHERE B_CODE = 'report' GROUP BY B_TYPE ORDER BY CNT DESC ");
  $total = 0;
  $rows = array();
  while ($row = sql_fetch_array($result)) {
      $rows[] = $row;
      $total += $row['CNT'];
  }

  if ($rows) {
      echo "<table>";
      echo "<tr><th>B_TYPE</th><th>건수</th><th>비고</th></tr>";
      foreach ($rows as $row) {
          $type = $row['B_TYPE'];
          $cnt  = $row['CNT'];
          $is_final     = in_array($type, $target_types);
          $is_migration = in_array($type, array('Trend Delivery', 'VOICE Trend', 'Trend Overview', 'Trend Report'));
          $is_news      = ($type === 'News');

          if ($is_final) {
              $note = "<span class='ok'>&#10003; 최종 타입 (유지)</span>";
          } elseif ($is_migration) {
              $note = "<span class='warn'>&#8594; 'Report' 로 마이그레이션 대상</span>";
          } elseif ($is_news) {
              $note = "<span class='warn'>&#9888; 주석 처리된 쿼리 대상 &mdash; 확인 필요</span>";
          } elseif (empty($type)) {
              $note = "<span class='err'>&#10007; B_TYPE 없음 (빈값)</span>";
          } else {
              $note = "<span class='err'>&#10007; 미처리 타입 &mdash; IN 절에 없음</span>";
          }

          echo "<tr><td>" . htmlspecialchars($type ?: '(빈값)') . "</td><td>{$cnt}</td><td>{$note}</td></tr>";
      }
      echo "<tr style='background:#f0f0f0'><td><strong>TOTAL</strong></td><td><strong>{$total}</strong></td><td></td></tr>";
      echo "</table>";
  } else {
      echo "<p class='warn'>데이터 없음</p>";
  }
  ?>
</div>

<?php
// -------------------------------------------------------
// 3. 마이그레이션 영향 건수
// -------------------------------------------------------
?>
<div class="section">
  <h3>3. db_update.php 각 UPDATE 영향 건수 (예상)</h3>
  <table>
    <tr><th>쿼리</th><th>대상 건수</th><th>상태</th></tr>
    <?php
    // UPDATE 1: 구형 타입들 → Report
    $r1 = sql_fetch(" SELECT COUNT(*) AS CNT FROM T_BOARD WHERE B_CODE = 'report' AND B_TYPE IN ('Trend Delivery', 'VOICE Trend', 'Trend Overview', 'Trend Report') ");
    $cnt1 = (int)$r1['CNT'];
    echo "<tr>";
    echo "<td>B_TYPE IN ('Trend Delivery','VOICE Trend','Trend Overview','Trend Report') → 'Report'</td>";
    echo "<td>{$cnt1}</td>";
    echo "<td class='" . ($cnt1 > 0 ? 'warn' : 'ok') . "'>" . ($cnt1 > 0 ? "&#9888; {$cnt1}건 변경됨" : "&#10003; 해당 없음 (이미 처리됨)") . "</td>";
    echo "</tr>";

    // UPDATE 2 (주석 처리됨): News → Report
    $r2 = sql_fetch(" SELECT COUNT(*) AS CNT FROM T_BOARD WHERE B_CODE = 'report' AND B_TYPE = 'News' ");
    $cnt2 = (int)$r2['CNT'];
    echo "<tr>";
    echo "<td style='color:#888'>[주석] B_TYPE = 'News' → 'Report'</td>";
    echo "<td>{$cnt2}</td>";
    echo "<td class='" . ($cnt2 > 0 ? 'err' : 'ok') . "'>" . ($cnt2 > 0 ? "&#10007; {$cnt2}건 존재 &mdash; 주석 해제 여부 결정 필요" : "&#10003; 해당 없음") . "</td>";
    echo "</tr>";
    ?>
  </table>
</div>

<?php
// -------------------------------------------------------
// 4. 미처리 타입 확인
// -------------------------------------------------------
?>
<div class="section">
  <h3>4. 최종 타입 외 미처리 데이터 확인</h3>
  <p style="color:#888; font-size:13px">최종 타입: <?php echo implode(', ', $target_types); ?></p>
  <?php
  $known = array_merge($target_types, array('Trend Delivery', 'VOICE Trend', 'Trend Overview', 'Trend Report', 'News', ''));
  $placeholders = implode(',', array_fill(0, count($known), '?'));

  // PDO 없이 직접 escape 처리
  $escaped = array_map(function($v) { return "'" . addslashes($v) . "'"; }, $known);
  $in_str  = implode(',', $escaped);

  $result2 = sql_query(" SELECT B_TYPE, COUNT(*) AS CNT FROM T_BOARD WHERE B_CODE = 'report' AND B_TYPE NOT IN ({$in_str}) GROUP BY B_TYPE ");
  $extra = array();
  while ($row = sql_fetch_array($result2)) {
      $extra[] = $row;
  }

  if ($extra) {
      echo "<p class='err'>&#10007; IN 절에 없는 타입 발견:</p>";
      echo "<table><tr><th>B_TYPE</th><th>건수</th></tr>";
      foreach ($extra as $row) {
          echo "<tr><td>" . htmlspecialchars($row['B_TYPE']) . "</td><td>{$row['CNT']}</td></tr>";
      }
      echo "</table>";
  } else {
      echo "<p class='ok'>&#10003; 미처리 타입 없음 &mdash; 모든 데이터가 처리 범위 내에 있습니다.</p>";
  }
  ?>
</div>

</body>
</html>
