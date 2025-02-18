<!DOCTYPE html>
<html style="user-select: none;">

<!---<html  data-layout-mode="detached" data-topbar-color="light" data-sidenav-color="light" style="user-select: none;">-->
<head>
    <meta charset="utf-8">
    @if(isset($business_settings))
        <meta content="{{$business_settings}}" type="business_setting"/>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ezziy - One Stop Ecommerce Solution</title>

    <!-- Fonts -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <style>

        .splash-screen .dark {
            display: none;
        }

        html[data-theme=dark] .splash-screen .dark {
            display: block;
        }

        html[data-theme=dark] .splash-screen .light {
            display: none;
        }

        .splash-screen.loader {
            position: fixed;
            top: 50%;
            left: 50%;
            background: white;
            display: flex;
            height: 100%;
            width: 100%;
            transform: translate(-50%, -50%);
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: all 15s linear;
            overflow: hidden;
        }

        html[data-theme=dark] .splash-screen.loader {
            background-color: #292e32;
        }

        div + .splash-screen.loader {
            animation: fadeout 0.7s forwards;
            z-index: 0;
        }

        @keyframes fadeout {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>

    <script>
        try {
            const config = JSON.parse(localStorage.getItem('layout_data'));
            const html = document.querySelector("html");
            if (html && config) {
                if (config.is_dark) {
                    html.setAttribute('data-theme', 'dark');
                    html.setAttribute('data-topbar-color', 'dark');
                    html.setAttribute('data-sidenav-color', 'dark');

                } else {
                    if (config.topbar?.color) {
                        html.setAttribute('data-topbar-color', config.topbar.color);
                    }
                    if (config.leftbar?.color) {
                        html.setAttribute('data-sidenav-color', config.leftbar.color);
                    }
                }
                if (config.mode) {
                    html.setAttribute('data-layout-mode', config.mode);
                }
                if (config.leftbar?.mode) {
                    html.setAttribute('data-sidenav-size', config.leftbar.mode);
                }
            }
        } catch (e) {
            console.info(e);
        }
    </script>

</head>

<body>
<div id="app">
    <router-view></router-view>

    <div class="splash-screen loader">
        <img class="light" style="height: 30%"
        src="/assets/images/logo/logo.png"
             alt="Logo"/>
        <!-- <img class="dark" style="height: 30%"
             src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2aWV3Qm94PSIxNDUuNjI2IC0yLjIxNSAyNjcuOTk1IDI1Ni4zNjkiIHdpZHRoPSIyNjcuOTk1IiBoZWlnaHQ9IjI1Ni4zNjkiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogIDxkZWZzPgogICAgPGxpbmVhckdyYWRpZW50IGlkPSJjb2xvci0wLTAtMCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSI3MS43OTIiIHkxPSI2LjQ1NCIgeDI9IjcxLjc5MiIgeTI9IjEzMi40NTQiIGdyYWRpZW50VHJhbnNmb3JtPSJtYXRyaXgoMS4xMDU0NjksIDAsIDAsIDEuMTA1NDY5LCAxOTUuMDcyNzIzLCAtMC42ODA2OTQpIiB4bGluazpocmVmPSIjY29sb3ItMC0wIi8+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImNvbG9yLTAtMCI+CiAgICAgIDxzdG9wIHN0eWxlPSJzdG9wLWNvbG9yOiByZ2IoNDYsIDI0MCwgMjA0KTsiIG9mZnNldD0iMCIvPgogICAgICA8c3RvcCBvZmZzZXQ9IjAuNiIgc3R5bGU9InN0b3AtY29sb3I6IHJnYig0MSwgMjMyLCAyNDIpOyIvPgogICAgICA8c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiByZ2IoNTEsIDE4NywgMjQ1KTsiLz4KICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgPC9kZWZzPgogIDxwYXRoIGQ9Ik0gMzI0LjE4MyA2LjQ1NCBMIDIyNC42OSA2LjQ1NCBDIDIxMy43MTMgNi40NTQgMjA0Ljc5MiAxNS4zODYgMjA0Ljc5MiAyNi4zNTIgTCAyMDQuNzkyIDM2Ljk0MyBDIDIwNC43OTIgNDIuMzI2IDIwNy4wMTQgNDcuNjIyIDIxMC44NzIgNTEuNDggQyAyMTQuNjQyIDU1LjI0OSAyMTkuNTI4IDU3LjI3MiAyMjQuNzEzIDU3LjMwNiBDIDIzMS40ODkgNTcuMjk1IDIzNy4yMTUgNTMuODU3IDI0MC40OTkgNDguNDYyIEMgMjQ0LjMyNCA1My43NTcgMjUwLjczNSA1Ny4zMDYgMjU3Ljg1NSA1Ny4zMDYgQyAyNjQuNzY0IDU3LjMwNiAyNzAuODY2IDUzLjc1NyAyNzQuNDM3IDQ4LjM5NSBDIDI3OC4wMDcgNTMuNzU3IDI4NC4xMDkgNTcuMzA2IDI5MS4wMTkgNTcuMzA2IEMgMjk3LjkyOCA1Ny4zMDYgMzA0LjAxOSA1My43NjggMzA3LjU5IDQ4LjQwNyBDIDMwOC40MDggNDkuNjIzIDMwOS4zNTggNTAuNzYxIDMxMC40NTMgNTEuOCBDIDMxMy4zODIgNTQuNTg2IDMxNi45NzUgNTYuMzY2IDMyMC44NjYgNTcuMDE4IEwgMzIwLjg2NiAxMzkuMTEgTCAyNzMuMzMxIDEzOS4xMSBMIDI3My4zMzEgODQuOTQyIEMgMjczLjMzMSA3Ny4wMTYgMjY2Ljg4NiA3MC41NzEgMjU4Ljk2IDcwLjU3MSBMIDIzNS43NDUgNzAuNTcxIEMgMjI3LjgxOSA3MC41NzEgMjIxLjM3NCA3Ny4wMTYgMjIxLjM3NCA4NC45NDIgTCAyMjEuMzc0IDEzOS4xMSBMIDIwOC4xMDggMTM5LjExIEMgMjA2LjI3MyAxMzkuMTEgMjA0Ljc5MiAxNDAuNTkyIDIwNC43OTIgMTQyLjQyNyBDIDIwNC43OTIgMTQ0LjI2MiAyMDYuMjczIDE0NS43NDMgMjA4LjEwOCAxNDUuNzQzIEwgMzQwLjc2NSAxNDUuNzQzIEMgMzQyLjYgMTQ1Ljc0MyAzNDQuMDgxIDE0NC4yNjIgMzQ0LjA4MSAxNDIuNDI3IEMgMzQ0LjA4MSAxNDAuNTkyIDM0Mi42IDEzOS4xMSAzNDAuNzY1IDEzOS4xMSBMIDMyNy40OTkgMTM5LjExIEwgMzI3LjQ5OSA1Ny4wMDcgQyAzMzYuOTYyIDU1LjQwNCAzNDQuMDgxIDQ3LjAxNCAzNDQuMDgxIDM2LjkzMiBMIDM0NC4wODEgMjYuMzUyIEMgMzQ0LjA4MSAxNS4zODYgMzM1LjE2IDYuNDU0IDMyNC4xODMgNi40NTQgWiBNIDIzNi44NTEgMzcuNDA3IEMgMjM2Ljg1MSA0NS4wOSAyMzEuNzQzIDUwLjY2MiAyMjQuNzAyIDUwLjY3MyBDIDIyNC43MDIgNTAuNjczIDIyNC42OSA1MC42NzMgMjI0LjY5IDUwLjY3MyBDIDIyMS4zMTkgNTAuNjczIDIxOC4wNjkgNDkuMjkxIDIxNS41NTkgNDYuNzgyIEMgMjEyLjkyOCA0NC4xNjIgMjExLjQyNSA0MC41NjkgMjExLjQyNSAzNi45NDMgTCAyMTEuNDI1IDI2LjM1MiBDIDIxMS40MjUgMTkuMDM0IDIxNy4zNzIgMTMuMDg3IDIyNC42OSAxMy4wODcgTCAyMzYuODUxIDEzLjA4NyBMIDIzNi44NTEgMzcuNDA3IFogTSAyNzEuMTIgMzcuNDA3IEMgMjcxLjEyIDQ0LjcyNSAyNjUuMTczIDUwLjY3MyAyNTcuODU1IDUwLjY3MyBDIDI1MC4xOTQgNTAuNjczIDI0My40ODMgNDQuNDcxIDI0My40ODMgMzcuNDA3IEwgMjQzLjQ4MyAxMy4wODcgTCAyNzEuMTIgMTMuMDg3IEwgMjcxLjEyIDM3LjQwNyBaIE0gMzA0LjI4NCAzNy40MDcgQyAzMDQuMjg0IDQ0LjcyNSAyOTguMzM3IDUwLjY3MyAyOTEuMDE5IDUwLjY3MyBDIDI4My43IDUwLjY3MyAyNzcuNzUzIDQ0LjcyNSAyNzcuNzUzIDM3LjQwNyBMIDI3Ny43NTMgMTMuMDg3IEwgMzA0LjI4NCAxMy4wODcgTCAzMDQuMjg0IDM3LjQwNyBaIE0gMjY2LjY5OCAxMzkuMTEgTCAyMjguMDA3IDEzOS4xMSBMIDIyOC4wMDcgODQuOTQyIEMgMjI4LjAwNyA4MC42NzUgMjMxLjQ3OCA3Ny4yMDQgMjM1Ljc0NSA3Ny4yMDQgTCAyNTguOTYgNzcuMjA0IEMgMjYzLjIyNyA3Ny4yMDQgMjY2LjY5OCA4MC42NzUgMjY2LjY5OCA4NC45NDIgTCAyNjYuNjk4IDEzOS4xMSBaIE0gMzM3LjQ0OCAzNi45NDMgQyAzMzcuNDQ4IDQ0LjE4NCAzMzEuNzg4IDUwLjMzIDMyNC44MjQgNTAuNjYyIEMgMzIxLjE1NCA1MC44MzkgMzE3LjY3MSA0OS41MzQgMzE1LjAxOCA0Ny4wMTQgQyAzMTIuMzc2IDQ0LjQ4MiAzMTAuOTE3IDQxLjA2NiAzMTAuOTE3IDM3LjQwNyBMIDMxMC45MTcgMTMuMDg3IEwgMzI0LjE4MyAxMy4wODcgQyAzMzEuNTAxIDEzLjA4NyAzMzcuNDQ4IDE5LjAzNCAzMzcuNDQ4IDI2LjM1MiBMIDMzNy40NDggMzYuOTQzIFoiIHN0eWxlPSJzdHJva2U6IHVybCgjY29sb3ItMC0wKTsgc3Ryb2tlLXdpZHRoOiA2LjhweDsgZmlsbDogdXJsKCNjb2xvci0wLTAtMCk7Ii8+CiAgPGcgaWQ9IlN2Z2pzRzE3NjYxIiBmaWxsPSIjMzgzOTQzIiBzdHlsZT0iIiB0cmFuc2Zvcm09Im1hdHJpeCgzLjUwNDEzNiwgMCwgMCwgMy4zNzE3MTEsIDE0Ny4xMTA5NzcsIDE2OS43Nzk3MDkpIj4KICAgIDxwYXRoIGQ9Ik0gNC45NTUgNC45IEwgMTAuODU1IDQuOSBDIDExLjU5NSA0LjkgMTIuMTk1IDUuNSAxMi4xOTUgNi4yNCBDIDEyLjE5NSA2Ljk4IDExLjU5NSA3LjU4IDEwLjg1NSA3LjU4IEwgNC45MzUgNy41OCBDIDMuOTU1IDcuNTggMy4xNzUgOC4zOCAzLjE3NSA5LjM0IEMgMy4xNzUgMTAuMzIgMy45NTUgMTEuMSA0LjkzNSAxMS4xIEwgOS4wMTUgMTEuMSBDIDExLjQ3NSAxMS4xIDEzLjQ1NSAxMy4xIDEzLjQ1NSAxNS41NiBDIDEzLjQ1NSAxOC4wMiAxMS40NzUgMjAgOS4wMTUgMjAgTCAyLjQ5NSAyMCBDIDEuNzU1IDIwIDEuMTU1IDE5LjQgMS4xNTUgMTguNjYgQyAxLjE1NSAxNy45NCAxLjczNSAxNy4zNCAyLjQ1NSAxNy4zMiBMIDkuMDE1IDE3LjMyIEMgOS45OTUgMTcuMzIgMTAuNzc1IDE2LjU0IDEwLjc3NSAxNS41NiBDIDEwLjc3NSAxNC42IDkuOTk1IDEzLjggOS4wMTUgMTMuOCBMIDQuOTU1IDEzLjggQyAyLjQ5NSAxMy44IDAuNDk1IDExLjgyIDAuNDk1IDkuMzYgQyAwLjQ5NSA2LjkgMi40OTUgNC45IDQuOTU1IDQuOSBaIE0gMjcuMTIxIDQuOSBDIDI3Ljg2MSA0LjkgMjguNDYxIDUuNSAyOC40NjEgNi4yMiBMIDI4LjQ2MSAxOC42NiBDIDI4LjQ2MSAxOS40IDI3Ljg2MSAyMCAyNy4xMjEgMjAgQyAyNi4zODEgMjAgMjUuODAxIDE5LjQgMjUuODAxIDE4LjY2IEwgMjUuODAxIDEzLjc2IEwgMTcuNjgxIDEzLjc2IEwgMTcuNjgxIDE4LjY2IEMgMTcuNjgxIDE5LjQgMTcuMDgxIDIwIDE2LjM2MSAyMCBDIDE1LjYyMSAyMCAxNS4wMjEgMTkuNCAxNS4wMjEgMTguNjYgTCAxNS4wMjEgNi4yMiBDIDE1LjAyMSA1LjUgMTUuNjIxIDQuOSAxNi4zNjEgNC45IEMgMTcuMDgxIDQuOSAxNy42ODEgNS41IDE3LjY4MSA2LjIyIEwgMTcuNjgxIDExLjA4IEwgMjUuODAxIDExLjA4IEwgMjUuODAxIDYuMjIgQyAyNS44MDEgNS41IDI2LjM4MSA0LjkgMjcuMTIxIDQuOSBaIE0gMzcuOTY3IDQuNDIgQyA0My4wNjcgNC40MiA0NS45ODcgOC43IDQ1Ljk4NyAxMi40NiBDIDQ1Ljk4NyAxNy4yMiA0Mi4xNDcgMjAuNDggMzcuOTY3IDIwLjQ4IEMgMzMuMTQ3IDIwLjQ4IDI5LjkyNyAxNi41OCAyOS45MjcgMTIuNDYgQyAyOS45MjcgOC40NiAzMy4xODcgNC40MiAzNy45NjcgNC40MiBMIDM2Ljk2NyA0LjQyIEwgMzUuOTY3IDQuNDIgTCAzNi45NjcgNC40MiBMIDM3Ljk2NyA0LjQyIEwgMzguOTY3IDQuNDIgTCAzOS45NjcgNC40MiBMIDM4Ljk2NyA0LjQyIEwgMzcuOTY3IDQuNDIgWiBNIDM3Ljk2NyA3LjEgQyAzNS4wNjcgNy4xIDMyLjU4NyA5LjQ0IDMyLjU4NyAxMi40NiBDIDMyLjU4NyAxNS40MiAzNC45ODcgMTcuODIgMzcuOTY3IDE3LjgyIEMgNDAuODQ3IDE3LjgyIDQzLjMyNyAxNS40NiA0My4zMjcgMTIuNDYgQyA0My4zMjcgOS41NiA0MC45NjcgNy4xIDM3Ljk2NyA3LjEgWiBNIDQ3LjcxMyA0LjkgTCA1NS4xMTMgNC45IEMgNTcuNTczIDQuOSA1OS41NTMgNi44OCA1OS41NTMgOS4zNCBDIDU5LjU1MyAxMS43NiA1Ny42MzMgMTMuNzIgNTUuMjMzIDEzLjggTCA1MS40NTMgMTMuODIgQyA1MC41OTMgMTMuODIgNDkuOTMzIDE0LjY2IDQ5LjkzMyAxNS41NiBMIDQ5LjkzMyAxOC42NiBDIDQ5LjkzMyAxOS40IDQ5LjMzMyAyMCA0OC41OTMgMjAgQyA0Ny44NTMgMjAgNDcuMjUzIDE5LjQgNDcuMjUzIDE4LjY2IEwgNDcuMjUzIDUuMzYgQyA0Ny4yNTMgNS4xIDQ3LjQ1MyA0LjkgNDcuNzEzIDQuOSBaIE0gNDkuOTMzIDcuNTggTCA0OS45MzMgMTEuNDYgQyA1MC40NzMgMTEuMjIgNTEuMDkzIDExLjEgNTEuNzEzIDExLjEgTCA1NS4xMTMgMTEuMSBDIDU2LjA5MyAxMS4xIDU2Ljg3MyAxMC4zMiA1Ni44NzMgOS4zNCBDIDU2Ljg3MyA4LjM4IDU2LjA5MyA3LjU4IDU1LjExMyA3LjU4IEwgNDkuOTMzIDcuNTggWiIgc3R5bGU9ImZpbGw6IHJnYigyMDMsIDIyNywgMjQ2KTsiLz4KICAgIDxwYXRoIGQ9Ik0gNzQuODE2IDUuMjI0IEMgNzUuMzM2IDUuNzY0IDc1LjMzNiA2LjYwNCA3NC44MTYgNy4xMjQgTCA2OC42MzYgMTMuMzA0IEwgNjguNjM2IDE4LjYwNCBDIDY4LjYzNiAxOS4zNDQgNjguMDM2IDE5Ljk0NCA2Ny4yNzYgMTkuOTQ0IEMgNjYuNTM2IDE5Ljk0NCA2NS45MzYgMTkuMzQ0IDY1LjkzNiAxOC42MDQgTCA2NS45MzYgMTMuMDQ0IEMgNjUuOTM2IDEyLjkwNCA2NS45NTYgMTIuNzY0IDY1Ljk3NiAxMi42MjQgTCA2MC40OTYgNy4xMjQgQyA1OS45NTYgNi42MDQgNTkuOTU2IDUuNzY0IDYwLjQ5NiA1LjIyNCBDIDYxLjAxNiA0LjcwNCA2MS44NTYgNC43MDQgNjIuMzc2IDUuMjI0IEwgNjcuNjU2IDEwLjUwNCBMIDcyLjkxNiA1LjIyNCBDIDczLjQzNiA0LjcwNCA3NC4yOTYgNC43MDQgNzQuODE2IDUuMjI0IFoiIHN0eWxlPSJmaWxsOiByZ2IoMjAzLCAyMjcsIDI0Nik7Ii8+CiAgPC9nPgo8L3N2Zz4="
             alt="Logo"/> -->
    </div>
</div>
</body>

<footer-script>
    <script src="{{mix('js/app.js')}}" defer></script>

    <link id="root-style-link"  rel="stylesheet" href="{{asset('css/app.css')}}"/>
    <link  rel="stylesheet" href="{{asset('css/icons.css')}}"/>

    @if(isset($business_settings))
        <script src="{{ "https://maps.googleapis.com/maps/api/js?key=". json_decode($business_settings)->google_map_api_key . "&libraries=drawing,places"}}"></script>
    @endif
</footer-script>

<script>
    if ("serviceWorker" in navigator) {
        window.addEventListener("load", function () {
            navigator.serviceWorker.register("/firebase-messaging-sw.js").then((registration) => {
                // registration worked
                registration.update();
            });
        });
    }
</script>
</html>


