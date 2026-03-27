<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

include_once(P1_PATH.'/head.sub.php');
?>
    <div id="wrap">
        <hr class="hr">
        <!-- header -->
        <div id="header">
            <div>
                <div class="dib fl MT5"><strong><?php echo $member['M_ID'] ?>님</strong></div>
                <button class="btn fl" type="button" onclick="location.href='<?php echo P1_PAGE_URL ?>/logout.php'">로그아웃</button>
            </div>
        </div>
        <!-- //header -->
        <!-- container -->
        <div id="container">
            <!-- snb -->
            <div id="snb">
                <ul>
                    <?php if ($member['M_AUTH_TP'] == "1") { ?>
					<li id="m0"<?=($p1['subtitle'] == "관리자 관리") ? " class='on'" : ""?>>
						<a href="<?php echo P1_PAGE_URL ?>/administration.php"<?=($p1['subtitle'] == "관리자 관리") ? " class='on'" : ""?>>관리자 관리</a>
					</li>
					<li id="m1"<?=($p1['subtitle'] == "게시판 설정 관리") ? " class='on'" : ""?>>
						<a href="<?php echo P1_PAGE_URL ?>/board.php"<?=($p1['subtitle'] == "게시판 설정 관리") ? " class='on'" : ""?>>게시판 설정 관리</a>
					</li>
                    <!-- <li id="m11"<?=($p1['subtitle'] == "메뉴 설정 관리") ? " class='on'" : ""?>> -->
						<!-- <a href="<?php echo P1_PAGE_URL ?>/menu.php"<?=($p1['subtitle'] == "메뉴 설정 관리") ? " class='on'" : ""?>>메뉴 설정 관리</a> -->
					<!-- </li> -->
                    <?php } ?>
                    <?php if (auth_check_no("a", "1")) { ?>
                    <li class="da<?=($p1['subtitle'] == "게시판 관리") ? " on" : ""?>" id="m2"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmpr"<?=($p1['subtitle'] == "게시판 관리") ? " class='on'" : ""?>>게시판 관리</a>
                        <ul class="sub">
                            <?php if (auth_check_no("aa", "2")) { ?><li id="m2-1"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmpr"<?=($p1['title'] == "회사소식") ? " class='on'" : ""?>>회사소식</a></li><?php } ?>
                            <?php if (auth_check_no("ab", "2")) { ?><li id="m2-2"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmnw"<?=($p1['title'] == "뉴스레터") ? " class='on'" : ""?>>뉴스레터</a></li><?php } ?>
                            <?php if (auth_check_no("ac", "2")) { ?><li id="m2-3"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmexp"<?=($p1['title'] == "칼럼관리") ? " class='on'" : ""?>>칼럼관리</a></li><?php } ?>
                            <?php if (auth_check_no("ad", "2")) { ?><li id="m2-4"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmext"<?=($p1['title'] == "성공사례") ? " class='on'" : ""?>>성공사례</a></li><?php } ?>
                            <!--
							<?php if (auth_check_no("ae", "2")) { ?><li id="m2-5"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=nsmfaq"<?=($p1['title'] == "FAQ") ? " class='on'" : ""?>>FAQ</a></li><?php } ?>
							-->
                            <?php if (auth_check_no("af", "2")) { ?><li id="m2-6"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=playdprivate"<?=($p1['title'] == "개인정보처리방침") ? " class='on'" : ""?>>개인정보처리방침</a></li><?php } ?>
                            <?php if (auth_check_no("ag", "2")) { ?><li id="m2-7"><a href="<?php echo P1_PAGE_URL ?>/post.php?bc_code=playdportfolio"<?=($p1['title'] == "포트폴리오") ? " class='on'" : ""?>>포트폴리오</a></li><?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if (auth_check_no("b", "1")) { ?>
                    <li class="db<?=($p1['subtitle'] == "문의관리") ? " on" : ""?>" id="m2"><a href="<?php echo P1_PAGE_URL ?>/qna.php?bc_code=nsmad"<?=($p1['subtitle'] == "문의관리") ? " class='on'" : ""?>>문의관리</a>
                        <ul class="sub">
                            <?php if (auth_check_no("ba", "2")) { ?><li id="m3-1"><a href="<?php echo P1_PAGE_URL ?>/qna.php?bc_code=nsmad"<?=($p1['title'] == "광고문의") ? " class='on'" : ""?>>광고문의</a></li><?php } ?>
                            <?php if (auth_check_no("bb", "2")) { ?><li id="m3-2"><a href="<?php echo P1_PAGE_URL ?>/qna.php?bc_code=nsmasso"<?=($p1['title'] == "제휴문의") ? " class='on'" : ""?>>제휴문의</a></li><?php } ?>
                            <?php if (auth_check_no("bc", "2")) { ?><li id="m3-3"><a href="<?php echo P1_PAGE_URL ?>/report.php"<?=($p1['title'] == "윤리경영제보") ? " class='on'" : ""?>>윤리경영제보</a></li><?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if (auth_check_no("c", "1")) { ?>
                    <li class="dc<?=($p1['subtitle'] == "채용관리(PLAYD)") ? " on" : ""?>" id="m3"><a href="<?php echo P1_PAGE_URL ?>/incident.php"<?=($p1['title'] == "입사지원관리(PLAYD)") ? " class='on'" : ""?>>입사지원관리(PLAYD)</a>
                        <ul class="sub">
                            <?php if (auth_check_no("ca", "2")) { ?><li id="m4-1"><a href="<?php echo P1_PAGE_URL ?>/incident.php"<?=($p1['title'] == "입사지원관리(PLAYD)") ? " class='on'" : ""?>>입사지원관리(PLAYD)</a></li><?php } ?>
                            <?php if (auth_check_no("cb", "2")) { ?><li id="m4-2"><a href="<?php echo P1_PAGE_URL ?>/qna.php?bc_code=nsmjobask"<?=($p1['title'] == "채용문의(PLAYD)") ? " class='on'" : ""?>>채용문의(PLAYD)</a></li><?php } ?>
                            <?php if (auth_check_no("cc", "2")) { ?><li id="m4-3"><a href="<?php echo P1_PAGE_URL ?>/pl_incident.php"<?=($p1['title'] == "인재풀관리") ? " class='on'" : ""?>>인재풀관리</a></li><?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if (auth_check_no("d", "1")) { ?>
                    <li class="dd<?=($p1['subtitle'] == "채용관리(MABLE)") ? " on" : ""?>" id="m4"><a href="<?php echo P1_PAGE_URL ?>/m_incident.php"<?=($p1['title'] == "입사지원관리(MABLE)") ? " class='on'" : ""?>>채용관리(MABLE)</a>
                        <ul class="sub">
                            <?php if (auth_check_no("da", "2")) { ?><li id="m5-1"><a href="<?php echo P1_PAGE_URL ?>/m_incident.php"<?=($p1['title'] == "입사지원관리(MABLE)") ? " class='on'" : ""?>>입사지원관리(MABLE)</a></li><?php } ?>
                            <?php if (auth_check_no("db", "2")) { ?><li id="m5-2"><a href="<?php echo P1_PAGE_URL ?>/qna.php?bc_code=mable_jobask"<?=($p1['title'] == "채용문의(MABLE)") ? " class='on'" : ""?>>채용문의(MABLE)</a></li><?php } ?>
                        </ul>
					</li>
                    <?php } ?>
                    <?php if (auth_check_no("e", "1")) { ?>
                    <li class="de<?=($p1['subtitle'] == "뉴스레터 신청자") ? " on" : ""?>" id="m5">
                        <a href="<?php echo P1_PAGE_URL ?>/newsletter.php"<?=($p1['subtitle'] == "뉴스레터 신청자") ? " class='on'" : ""?>>뉴스레터 신청자</a>
                    </li>
                    <?php } ?>
                    <?php if (auth_check_no("f", "1")) { ?>
                    <li class="df" id="m6">
                        <a href="/manager/file_upload/file_upload.do">파일 업로드</a>
                    </li>
                    <?php } ?>
					<!-- <li id="m7"> -->
						<!-- <a href="/manager/main/main_list.do">메인 관리</a> -->
					<!-- </li> -->
                </ul>
            </div>
            
            <!-- //snb -->
            <!-- content -->
            <div id="content">
                <div class="cntHeader">
                    <h2><?=($p1['title']) ? $p1['title'] : $p1['subtitle']?></h2>
                    <ul>
                        <li>홈</li>
                        <li><?=$p1['subtitle']?></li>
                        <?php if ($p1['title']) { ?>
                        <li class="last"><?=$p1['title']?></li>
                        <?php } ?>
                    </ul>
                </div>