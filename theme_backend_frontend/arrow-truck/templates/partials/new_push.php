<?php

    $date = new DateTime( 'now', new DateTimeZone( 'America/Chicago' ) );

?>

<div
    class="jc-fixed-wrapper"
    style="position: fixed;
        z-index: 100000;
        top: 2rem;
        left: 2rem;
        background-color: lightgreen;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.75rem;
        color: rgba(0,0,0,0.5);
        padding: 0.25rem 1.5rem;
        opacity: 0.95;
        border-radius: 1rem;
        font-weight: bold;">

    <span>
        PUSHED ON <?php echo $date->format( 'n.j.y @ g:i A' ); ?>
    </span>
</div>
