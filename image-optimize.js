const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const inputDir = path.join(__dirname, 'assets/images');
const outputDir = path.join(__dirname, 'dist');

function processDirectory(directory) {
  const items = fs.readdirSync(directory);

  items.forEach(item => {
    const inputPath = path.join(directory, item);
    const stat = fs.statSync(inputPath);

    if (stat.isDirectory()) {
      processDirectory(inputPath);
      return;
    }

    const ext = path.extname(item).toLowerCase();

    if (!['.jpg', '.jpeg', '.png'].includes(ext)) {
      return;
    }

    const relativePath = path.relative(
      inputDir,
      inputPath
    );

    const outputPath = path.join(
      outputDir,
      relativePath
    );

    fs.mkdirSync(path.dirname(outputPath), {
      recursive: true
    });

    console.log(`Optimizing: ${relativePath}`);

    let sharpInstance = sharp(inputPath);

    if (ext === '.jpg' || ext === '.jpeg') {
      sharpInstance
        .jpeg({
          quality: 80,
          mozjpeg: true
        })
        .toFile(outputPath)
        .then(() => {
          console.log(`Saved: ${outputPath}`);
        })
        .catch(console.error);
    }

    if (ext === '.png') {
      sharpInstance
        .png({
          compressionLevel: 9,
          quality: 80
        })
        .toFile(outputPath)
        .then(() => {
          console.log(`Saved: ${outputPath}`);
        })
        .catch(console.error);
    }
  });
}

processDirectory(inputDir);
