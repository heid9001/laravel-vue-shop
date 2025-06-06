<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lara Shop</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <hello-world
            :message="message"
            @update:message="message = $event"
        ></hello-world>
    </div>
</body>
</html>
