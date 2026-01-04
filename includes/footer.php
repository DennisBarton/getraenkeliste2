<?php
$today = date('d.m.Y');
$now = date('H:i:s'); ?>
</article>
<footer>
  <p>Seite abgerufen am <?=$today?> um <?=$now?> Uhr.</p>
  <button onclick="refreshCSS()">
    Refresh CSS
  </button>
</footer>

<script>
    refreshCSS = () => {
        let links = document.getElementsByTagName('link');
        for (let i = 0; i < links.length; i++) {
            if (links[i].getAttribute('rel') == 'stylesheet') {
                let href = links[i].getAttribute('href')
                                        .split('?')[0];

                let newHref = href + '?version='
                            + new Date().getMilliseconds();

                links[i].setAttribute('href', newHref);
            }
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>

