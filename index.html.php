<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scrapper</title>
</head>
<body>
    <form action="GET">
        <label for="url">Input url</label>
        <input type="text" name="url" id="url" value="<?php echo $url; ?>">
        <button type="submit">Scrap</button>
    </form>
    <?php if ($success) { ?>
        <span>Success scrap!</span>
    <?php } ?>
</body>
</html>