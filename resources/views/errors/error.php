<style>

    .error {
        width: 800px;
        height: 100%;
        background: beige;
        margin: 0 auto;
    }

    .error h1 {
        text-align: center;    
        background: grey;
        padding: 10px;
        font-size: 40px;
    }

    .error #content {
        padding: 20px;
    }

</style>

<div class = "error">
    <h1>Error</h1>
    <div id = "content">
        <h2><?php echo $status; ?></h2>
        <p><?php echo $message; ?></p>
    </div>
</div>



