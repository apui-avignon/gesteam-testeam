<meta http-equiv="refresh" content="3;URL=?index.php">
<p class="h2">Oooh... Il semblerait que vous ayez été déconnecté ! <br>
  La page va être rechargé dans <span id="timer"> 4 </span> secondes. En cas de problème persistant, contactez l'administrateur du site</p>

<script>
  function timer() {
    if (parseInt(document.getElementById('timer').innerText) > 0) {
      document.getElementById('timer').innerText = parseInt(document.getElementById('timer').innerText) - 1
      setTimeout('timer()', 1000);
    }
  }
  timer();
</script>