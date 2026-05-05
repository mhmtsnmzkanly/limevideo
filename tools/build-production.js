const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { execSync } = require('child_process');
const esbuild = require('esbuild');

async function build() {
    const root = path.resolve(__dirname, '..');
    const publicDir = path.join(root, 'public');
    const uiDir = path.join(root, 'ui');

    if (!fs.existsSync(publicDir)) fs.mkdirSync(publicDir);

    console.log('Building production assets...');

    try {
        // 1. Compile Tailwind
        console.log('- Compiling Tailwind CSS...');
        const tailwindInput = path.join(uiDir, 'tailwind.input.css');
        const tailwindOutput = path.join(publicDir, 'tailwind.tmp.css');
        const tailwindConfig = path.join(__dirname, 'tailwind.config.js');
        execSync(`npx tailwindcss --config ${tailwindConfig} -i ${tailwindInput} -o ${tailwindOutput} --minify`);

        // 2. Combine Tailwind + Custom CSS
        console.log('- Bundling CSS...');
        const customCss = fs.readFileSync(path.join(uiDir, 'index.css'), 'utf8');
        const twCss = fs.readFileSync(tailwindOutput, 'utf8');
        const finalCss = twCss + '\n' + customCss;
        
        // 3. Minify final CSS
        const minCssResult = await esbuild.transform(finalCss, { loader: 'css', minify: true });
        const cssContent = minCssResult.code;
        const cssHash = crypto.createHash('sha256').update(cssContent).digest('hex').slice(0, 10);
        fs.writeFileSync(path.join(publicDir, 'index.css'), cssContent);
        fs.unlinkSync(tailwindOutput);

        // 4. Minify JS
        console.log('- Minifying JS...');
        const jsSource = fs.readFileSync(path.join(uiDir, 'index.js'), 'utf8');
        const minJsResult = await esbuild.transform(jsSource, { minify: true, target: 'es2015' });
        const jsContent = minJsResult.code;
        const jsHash = crypto.createHash('sha256').update(jsContent).digest('hex').slice(0, 10);
        fs.writeFileSync(path.join(publicDir, 'index.js'), jsContent);

        // 5. Generate public/index.html
        console.log('- Generating production HTML...');
        let html = fs.readFileSync(path.join(uiDir, 'index.html'), 'utf8');
        
        // Remove Tailwind CDN
        html = html.replace(/<script src="https:\/\/cdn\.tailwindcss\.com"><\/script>/, '');
        
        // Update asset paths with hashes
        html = html.replace(/\/ui\/index\.css/, `/public/index.css?v=${cssHash}`);
        html = html.replace(/\/ui\/index\.js/, `/public/index.js?v=${jsHash}`);

        fs.writeFileSync(path.join(publicDir, 'index.html'), html);

        console.log('\nBuild Successful!');
        console.log(`CSS: ${cssContent.length} bytes, Hash: ${cssHash}`);
        console.log(`JS:  ${jsContent.length} bytes, Hash: ${jsHash}`);
        console.log('Production shell written to /public/index.html');

    } catch (err) {
        console.error('\nBuild Failed:', err.message);
        process.exit(1);
    }
}

build();
