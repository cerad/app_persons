<!doctype html>
<html lang="en">
<head>
  <title>OAuth CB</title>
</head>
<body>
  <table>
    <tr><td>Provider  </td><td><?php echo $userInfo['providername']; ?></td></tr>
    <tr><td>Identifier</td><td><?php echo $userInfo['identifier'  ]; ?></td></tr>
    <tr><td>User Name </td><td><?php echo $userInfo['nickname'    ]; ?></td></tr>
    <tr><td>Real Name </td><td><?php echo $userInfo['realname'    ]; ?></td></tr>
    <tr><td>Email     </td><td><?php echo $userInfo['email'       ]; ?></td></tr>
    <tr><td>Token     </td><td><?php echo $accessToken;              ?></td></tr>
  </table>
  <script>
    var token = '<?php echo $accessToken; ?>';
    window.opener.oauthCallback('<?php echo $json; ?>');
  </script>
</body>
</html>
