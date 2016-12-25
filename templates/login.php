<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Lees :: Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/main.css">
  </head>
  <body>

    <div class="container">
      <p>Welcome to Lees. You can only enter if you are me, but you can host <a href="https://github.com/sebsel/lees">this thing</a> yourself.</p>

      <form action="https://indieauth.com/auth" method="get">
        <label for="indie_auth_url">Web Address:</label>
        <input id="indie_auth_url" type="url" name="me" placeholder="https://yourdomain.com" />
        <button type="submit">Sign In</button>
        <input type="hidden" name="client_id" value="<?=url::base()?>/" />
        <input type="hidden" name="redirect_uri" value="<?=url::base()?>/auth" />
      </form>
    </div>
  </body>
</html>
