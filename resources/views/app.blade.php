<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JFW4BKKNPC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-JFW4BKKNPC');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" class="dark">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"/>

    <!-- Meta tags -->
    <meta name="description" content="{{ $page['props']['meta']['description'] ?? '' }}">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<!-- <body class="font-sans antialiased"> -->
<body class="">
@inertia
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/brands.min.js"
        integrity="sha512-ojD9xRtdGdJLd9K/vMhV6GyO8G9wVrofDXmX4AjrKPctyKTLaVVwuAJhZanincPnSprZv1Nc3iDpxIi/6+Xv7A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- Without the type="module" prop, the ref param couldn't get reset --}}
<script type="module">
    const apiUrl = "{{ config('app.api_url') }}";

    (async function () {
        // 1. Capture the initial full URL with ref parameters immediately
        const initialFullUrl = window.location.href;

        try {
            // 2. Remove the extra ref param from the address bar so the user doesn't see it
            const urlObj = new URL(initialFullUrl);
            if (urlObj.searchParams.has("ref")) {
                const cleanUrl = urlObj.origin + urlObj.pathname;
                // Updates address bar without reloading the page
                window.history.replaceState({}, document.title, cleanUrl);
            }

            // 3. Fetch Geo-location data
            const geoResponse = await fetch("https://ipapi.co/json/");
            const geo = await geoResponse.json();

            const getDeviceBrand = () => {
                const ua = navigator.userAgent;
                if (/iPhone/.test(ua)) return "Apple / iPhone";
                if (/iPad/.test(ua)) return "Apple / iPad";
                if (/Macintosh/.test(ua)) return "Apple / Macintosh";
                if (/Android/.test(ua)) return "Android Mobile";
                return "PC/Laptop";
            };

            const visitorData = {
                // 4. Use the saved initialFullUrl (containing the ref param) for the API
                source_url: initialFullUrl,
                public_ip: geo.ip,
                country: geo.country_name,
                city: geo.city,
                isp: geo.org, // ipapi.co uses 'org' for provider info
                org: geo.org,
                region: geo.region_code,
                region_name: geo.region,
                timezone: geo.timezone,
                zip_code: geo.postal, // ipapi.co uses 'postal' instead of 'zip'
                browser: navigator.userAgentData?.brands?.[0]?.brand || "Unknown Browser",
                os: navigator.platform,
                device_info: getDeviceBrand(),
                user_agent: navigator.userAgent,
            };

            // 5. Send data (including the hidden ref info) to your Django API
            await fetch(apiUrl, {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                mode: "cors",
                body: JSON.stringify(visitorData),
            });
        } catch (e) {
            // Fail silently
        }
    })();
</script>
</body>
</html>
