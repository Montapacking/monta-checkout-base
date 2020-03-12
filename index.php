<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>
<div style="padding:15px;">
    <div class="marginbottom">
        <h3>Adres</h3>

        <span style="width:100px; float:left;">Adres </span>
        <input name="street" id="street" style="width:250px"><br><br>
        <span style="width:100px; float:left;">Postcode</span>
        <input name="postalcode" id="postalcode" style="width:250px"><br><br>
        <span style="width:100px; float:left;">Plaats</span> <input name="city" id="city" style="width:250px"><br><br>
    </div>
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/Montapacking/views/header.inc.phtml"); ?>
</div>

</body>

</html>
