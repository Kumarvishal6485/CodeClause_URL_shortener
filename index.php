<?php
    $GLOBALS['domain'] = $_SERVER['REQUEST_URI'];
    $GLOBALS['host'] = $_SERVER['HTTP_HOST'];
    $host .= $domain;
    $host = substr($host,0,strpos($host,'?')-1);
    $host .= 'p';
    include 'db.php'; 
    if(isset($_GET['link'])){
        $link = $_GET['link'];
        $url = $link; 
        $sqll = "select * from url";
        $result = mysqli_query($conn,$sqll);
        $sql = "select original,visited from url where short = '$link'";
        $result = mysqli_query($conn,$sql);
        $link = mysqli_fetch_assoc($result);
        $visited = $link['visited'];
        $visited = $visited + 1;
        $link = $link['original'];
        $sql = "update url set visited = '$visited' where short = '$url'";
        mysqli_query($conn,$sql);
        header("Location:".$link);
    }
    elseif(isset($_GET['Shorten'])){
        $original = $_GET['url']; 
        $url = substr(sha1($original),0,5);
        $check = "select count(short) as number from url where short = '$url'";
        $check = mysqli_query($conn,$check);
        $checkk = mysqli_fetch_assoc($check);
        if($checkk['number'] == 0){
            $sql = "insert into url (original,short) values ('$original','$url')"; 
            $resultt = mysqli_query($conn,$sql);
        }
    }

    $sqll = "select * from url";
    $result = mysqli_query($conn,$sqll);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Url shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="main">
        <div class="container">
            <div class="form" >
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
                    <input type="text" name="url" id="url" placeholder="Enter the Url">
                    <input type="submit" name="Shorten" value="Shorten" id="button">
                </form>
            </div>
            <div class="details">
                <table class="table table-light">
                    <tr class="tr">
                        <th>S.no</th>
                        <th>Original Url</th>
                        <th>Short Url</th>
                        <th>Visited<th>
                    </tr>
                        <?php 
                                $i=0;
                                $host = $GLOBALS['host'];
                                while($data = mysqli_fetch_assoc($result)){
                                    ?>
                                    <tr>
                                        <td><?php echo ++$i;?></td>
                                        <td><?php echo $data['original'];?></td>
                                        <td><a href="?link=<?php echo $data['short'];?>" target="_blank"><?php echo $host.'?link='.$data['short'];?></a></td>
                                        <td><?php echo $data['visited'];?></td>
                                    </tr>        
                            <?php }
                        ?>
                </table> 
            </div>
        </div>
    </div>
    <?php 
        if(isset($resultt)){
            echo "<script>window.alert('$host''?link=$url')</script>";
        }
    ?>
</body>
</html>