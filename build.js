const fs = require('fs');
const path = require('path');
const { minify } = require('terser');
const sass = require('sass');
const postcss = require('postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');

// Read package information dynamically from package.json
const packageJson = require('./package.json');
const version = packageJson.version;
const phpversion = packageJson.phpversion;
const modxversion = packageJson.modxversion;
const currentYear = new Date().getFullYear();
const startYear = parseInt(packageJson.startYear) || currentYear;
const yearRange = currentYear > startYear ? `${startYear}-${currentYear}` : `${startYear}`;
const dateStr = new Date().toISOString()
    .split('T')[0];

const copyrightRegex = new RegExp(`Copyright ${startYear}(-\\d{4})? by`, 'g');
const copyrightReplace = `Copyright ${yearRange} by`;
const apiRegex = new RegExp(`&copy; ${startYear}(-\\d{4})?`, 'g');
const apiReplace = `&copy; ${yearRange}`;
const banner = `/*!\n * ${packageJson.fullname} - ${packageJson.description}\n * Version: ${packageJson.version}\n * Build date: ${dateStr}\n */\n`;

let versionParts = version.split('-');
let versionNumber = versionParts[0];
let versionRelease = versionParts[1] || 'pl';
let versionFull = versionNumber + '-' + versionRelease;

// Helper: Replace string/regex in a file
function replaceInFile(filePath, replacements, message = 'file') {
    if (!fs.existsSync(filePath)) {
        console.warn(`⚠ File not found: ${filePath}`);
        return;
    }
    let content = String(fs.readFileSync(filePath, 'utf8'));
    replacements.forEach(({ search, replace }) => {
        content = content.replace(search, replace);
    });
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`✓ Updated ${message}: ${filePath}`);
}

// Helper: Ensure directory structure exists
function ensureDirExists(dirPath) {
    if (!fs.existsSync(dirPath)) {
        fs.mkdirSync(dirPath, { recursive: true });
    }
}

// Helper: Copy a file
function copyFile(src, dest) {
    const fullSource = path.resolve(__dirname, src);
    const fullTarget = path.resolve(__dirname, dest);
    if (!fs.existsSync(fullSource)) {
        console.warn(`⚠ Source file not found for copy: ${src}`);
        return;
    }
    const targetDir = path.dirname(fullTarget);
    ensureDirExists(targetDir);
    fs.copyFileSync(fullSource, fullTarget);
    console.log(`✓ Copied: ${src} -> ${dest}`);
}

// Helper: Copy folders recursively with an optional filter function
async function copyFolderRecursive(src, dest, filterFn = (fileName) => true) {
    if (!fs.existsSync(src)) {
        console.warn(`⚠ Source file not found for copy: ${src}`);
        return;
    }
    ensureDirExists(dest);
    const entries = fs.readdirSync(src, { withFileTypes: true });

    for (let entry of entries) {
        const srcPath = path.join(src, entry.name);
        const destPath = path.join(dest, entry.name);

        if (entry.isDirectory()) {
            await copyFolderRecursive(srcPath, destPath, filterFn);
        } else if (filterFn(entry.name)) {
            fs.copyFileSync(srcPath, destPath);
            console.log(`✓ Copied: ${srcPath} -> ${destPath}`);
        }
    }
}

// Helper function to compile scripts
async function compileScripts(files, dest, filename) {
    console.log('Compiling scripts...');
    let combinedCode = files.map(f => fs.readFileSync(f, 'utf8'))
        .join('\n');
    const minified = await minify(combinedCode, {
        mangle: true,
        compress: true,
        format: {
            comments: false,
        },
    });
    const finalCode = banner + minified.code;
    ensureDirExists(dest);
    filename = filename.replace(/(\.\w+)$/i, '.min$1');
    fs.writeFileSync(path.join(dest, filename), finalCode, 'utf8');
}

// Helper function to compile, autoprefix and minify Sass
async function compileSass(src, intermediate, dest, filename) {
    console.log('Compiling Sass & processing CSS...');
    const sassResult = sass.compile(src, { style: 'expanded' });
    ensureDirExists(intermediate);
    fs.writeFileSync(path.join(intermediate, filename), sassResult.css, 'utf8');
    const postcssResult = await postcss([
        autoprefixer(),
        cssnano({ preset: ['default', { discardComments: { removeAll: true } }] })
    ])
        .process(sassResult.css, { from: undefined });
    const finalCss = postcssResult.css + '\n' + banner;
    ensureDirExists(dest);
    filename = filename.replace(/(\.\w+)$/i, '.min$1');
    fs.writeFileSync(path.join(dest, filename), finalCss, 'utf8');
}

async function taskBump() {
    console.log(`Bump (with version ${versionFull} and daterange: ${yearRange})...`);
    const copyrightFiles = [
        'core/components/customrequest/model/customrequest/customrequest.class.php',
        'core/components/customrequest/src/CustomRequest.php',
    ];
    copyrightFiles.forEach(file => {
        replaceInFile(file, [{
            search: copyrightRegex,
            replace: copyrightReplace
        }], 'copyright in');
    });
    replaceInFile('core/components/customrequest/src/CustomRequest.php', [{
        search: /version = '\d+\.\d+\.\d+-?[0-9a-z]*'/ig,
        replace: `version = '${version}'`
    }], 'version in');
    replaceInFile('source/js/mgr/widgets/home.panel.js', [{
        search: apiRegex,
        replace: apiReplace,
    }], 'daterange in');
    const docFiles = [
        'zensical.toml',
    ];
    docFiles.forEach(file => {
        replaceInFile(file, [{
            search: apiRegex,
            replace: apiReplace
        }], 'daterange in');
    });
    replaceInFile('docs/index.md', [{
        search: /[*-] MODX Revolution \d.\d.*/g,
        replace: '* MODX Revolution ' + modxversion + '+'
    }, {
        search: /[*-] PHP (v)?\d.\d.*/g,
        replace: '* PHP ' + phpversion + '+'
    }], 'requirements in');
    replaceInFile('core/components/customrequest/composer.json', [{
        search: /"php": "\d.\d.*?"/g,
        replace: '"php": "' + phpversion + '"'
    }, {
        search: /"php": ">=\d.\d.*?"/g,
        replace: '"php": ">=' + phpversion + '"'
    }], 'requirements in');
    const buildFiles = [
        '_build/config.json',
        'core/components/customrequest/composer.json',
    ];
    buildFiles.forEach(file => {
        replaceInFile(file, [{
            search: /"version": "\d+\.\d+\.\d+-?[0-9a-z]*"/ig,
            replace: `"version": "${version}"`
        }], 'version in');
    });
}

async function taskScripts() {
    await compileScripts([
        'source/js/mgr/customrequest.js',
        'source/js/mgr/helper/combo.js',
        'source/js/mgr/helper/util.js',
        'source/js/mgr/widgets/home.panel.js',
        'source/js/mgr/widgets/configs.grid.js',
        'source/js/mgr/widgets/settings.panel.js',
        'source/js/mgr/sections/home.js'
    ], 'assets/components/customrequest/js/mgr/', 'customrequest.js');
}

async function taskSass() {
    await compileSass(
        'source/sass/mgr/customrequest.scss',
        'source/css/mgr/',
        'assets/components/customrequest/css/mgr/',
        'customrequest.css',
    );
}

async function taskImages() {
    console.log('Copying images...');
    const isImage = function (fileName) {
        return /\.(png|jpg|gif|svg)$/i.test(fileName);
    };
    await copyFolderRecursive('source/img', 'assets/components/customrequest/img', isImage);
}

const action = process.argv[2];
if (action === 'bump') {
    taskBump();
} else if (action === 'scripts') {
    taskScripts();
} else if (action === 'sass') {
    taskSass();
} else if (action === 'images') {
    taskImages();
} else {
    // Default: Beides ausführen
    taskBump()
        .then(() => taskScripts())
        .then(() => taskSass())
        .then(() => taskImages());
}

console.log('Done!');
