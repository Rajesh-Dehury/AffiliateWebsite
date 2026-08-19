const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

async function main() {
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    try {
        // First get a deal detail URL from the listing page
        const page = await browser.newPage();
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        await page.goto('https://www.indiafreestuff.in/deals/trending', { waitUntil: 'networkidle2', timeout: 30000 });
        await page.evaluate(() => new Promise(r => setTimeout(r, 2000)));

        // Get first deal's detail page URL and shop URL
        const dealInfo = await page.evaluate(() => {
            const item = document.querySelector('.product-item');
            if (!item) return null;
            const titleLink = item.querySelector('a.item-title');
            const shopBtn = item.querySelector('a.btn-shopnow');
            return {
                detailUrl: titleLink ? titleLink.getAttribute('href') : null,
                shopUrl: shopBtn ? shopBtn.getAttribute('href') : null,
                title: titleLink ? titleLink.textContent.trim() : null,
            };
        });

        console.log('Deal info:', JSON.stringify(dealInfo, null, 2));

        if (dealInfo && dealInfo.detailUrl) {
            // Visit the detail page
            console.log('\n--- Visiting deal detail page ---');
            const detailPage = await browser.newPage();
            await detailPage.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            await detailPage.goto(dealInfo.detailUrl, { waitUntil: 'networkidle2', timeout: 30000 });
            await page.evaluate(() => new Promise(r => setTimeout(r, 1000)));

            const detailInfo = await detailPage.evaluate(() => {
                // Look for the shop now button on detail page
                const shopBtn = document.querySelector('a.btn-shopnow');
                // Look for any Amazon links
                const amazonLinks = [];
                document.querySelectorAll('a[href*="amazon"]').forEach(a => {
                    amazonLinks.push({
                        text: (a.textContent || '').trim().substring(0, 50),
                        href: a.getAttribute('href')
                    });
                });
                // Check for redirect URL
                const redirectUrl = document.querySelector('a.redirect-url, .deal-redirect a');
                return {
                    title: document.title,
                    shopUrl: shopBtn ? shopBtn.getAttribute('href') : null,
                    finalUrl: window.location.href,
                    amazonLinks: amazonLinks.slice(0, 5),
                    redirectUrl: redirectUrl ? redirectUrl.getAttribute('href') : null,
                    // Check if there's an iframe or redirect script
                    hasIframe: document.querySelector('iframe') !== null,
                };
            });

            console.log('Detail page:', JSON.stringify(detailInfo, null, 2));

            // Check if the detail page has any links we can use
            const pageContent = await detailPage.content();
            console.log('Detail page length:', pageContent.length);
            
            // Search for ASIN in the page
            const bodyText = await detailPage.evaluate(() => document.body.innerText);
            const asinMatch = bodyText.match(/[A-Z0-9]{10}/);
            if (asinMatch) {
                console.log('Possible ASIN found in page:', asinMatch[0]);
            }

            await detailPage.close();
        }

        // Now try visiting the rto URL directly and capturing redirect
        if (dealInfo && dealInfo.shopUrl) {
            console.log('\n--- Visiting rto URL ---');
            // Create a new page and intercept requests
            const rtoPage = await browser.newPage();
            await rtoPage.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            // Capture all requests
            const navigatedUrls = [];
            rtoPage.on('request', request => {
                navigatedUrls.push(request.url());
            });
            
            await rtoPage.goto(dealInfo.shopUrl, { 
                waitUntil: 'domcontentloaded', 
                timeout: 30000 
            });
            await rtoPage.evaluate(() => new Promise(r => setTimeout(r, 3000)));

            console.log('Final URL after rto redirect:', rtoPage.url());
            console.log('Navigation chain:', navigatedUrls.slice(0, 5).join('\n  -> '));

            // Extract ASIN from final URL
            const finalUrl = rtoPage.url();
            const asinMatch = finalUrl.match(/\/(?:dp|gp\/product)\/([A-Z0-9]{10})/);
            const asinFromParam = finalUrl.match(/[?&]lp_page_asin=([A-Z0-9]{10})/);
            const asinFromDp = finalUrl.match(/[?&]ASIN=([A-Z0-9]{10})/);
            
            console.log('ASIN from /dp/:', asinMatch ? asinMatch[1] : null);
            console.log('ASIN from lp_page_asin:', asinFromParam ? asinFromParam[1] : null);
            console.log('ASIN from ASIN param:', asinFromDp ? asinFromDp[1] : null);

            // Also check the page content for ASIN
            const bodyText = await rtoPage.evaluate(() => document.body.innerText);
            console.log('Page title after redirect:', await rtoPage.title());
            
            await rtoPage.close();
        }

        await browser.close();
    } catch (err) {
        console.error('Error:', err.message);
        await browser.close();
    }
}

main();
