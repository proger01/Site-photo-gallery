<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My own page!</title>
    <link rel="stylesheet" href="/css/bulma.css">
    <link rel="stylesheet" href="/css/style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
</head>
<body>
    <div class="wrapper">
        <?= $this->insert('partials/navigation'); ?>
        <?= $this->section('content'); ?>
        <?= $this->insert('partials/footer'); ?>
    </div>
</body>
</html>