
<div class="transNav">
<nav id="nav">
    <input type="checkbox" id="menuToggle">
    <label for="menuToggle" class="menu-toggle">
        <i class="fas fa-bars"></i>
        <i class="fas fa-times"></i>
    </label>
    <ul>
        <li><a href="index.php?page=home">Home</a></li>
        <li><a href="index.php?page=news">Nieuws</a></li>
        <li><img style= "width:160px" height="67px" src="images/Logo.jpg" alt="Logo"></li>
        <li><a href="index.php?page=events">Events</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <br>
        {if $Login eq 'true'}<li><a href="index.php?page=logout">Logout</a></li>{/if}
        {if $Login eq 'false'}<li><a href="index.php?page=login">Login</a></li>{/if}

    </ul>
</nav>
</div>
