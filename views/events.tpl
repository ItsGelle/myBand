
<div id="eventPage">
    <br><br><br>
<h2 style="text-align: center">Events:</h2>
    <hr>

<form style="text-align: center" method="get" action="index.php">
    <input type="hidden" name="page" value="events">
    <input name="searchterm">
    <input type="submit" name="submit" value="zoek">

</form>
{*<h1>Number of pages: {$number_of_pages}</h1>*}

<p>
    {foreach from=$articles item=article}
    <hr>
    <br>
    <h2 style="text-align: center">{$article[0]}</h2>
    <p style="text-align: center">{$article[1]}</p>
{/foreach}
</p>

    <div class="pageNumber">
        <div class="buttons">
            {if $current_page gt 1}
        <a href="index.php?page=events&pageno={$current_page - 1}"><<</a>
        {/if}
        </div>

    <h3 style="text-align: center">{$current_page}</h3>

        <div class="buttons">
            {if $current_page lt $number_of_pages}
        <a href="index.php?page=events&pageno={$current_page + 1}">>></a>
        {/if}

        </div>
    </div>
</div>

