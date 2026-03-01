import asyncio
from playwright.async_api import async_playwright

async def run():
    async def capture(page, name, url):
        await page.goto(url)
        await asyncio.sleep(2)  # Wait for animations/load
        await page.screenshot(path=name, full_page=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()

        # Check U Vystřelenýho oka (ID 2 usually, let's find it)
        # We'll just go to /mista and click it
        await page.goto("http://localhost:8000/mista")
        await page.click("text=U Vystřelenýho oka")
        await asyncio.sleep(2)
        await page.screenshot(path="venue_single_stage.png", full_page=True)

        # Check map popup
        await page.goto("http://localhost:8000/")
        await asyncio.sleep(2)
        # Click a marker. Markers are Leaflet icons.
        await page.click(".leaflet-marker-icon")
        await asyncio.sleep(1)
        await page.screenshot(path="map_popup.png")

        await browser.close()

asyncio.run(run())
