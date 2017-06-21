<?php
	$userid = $_REQUEST['userid'];
	$comment_date = $_REQUEST['comment_date'];
	$comment_text = $_REQUEST['comment_text'];
	$parentid = $_REQUEST['parentid'];

	mysql_connect("localhost", "gigalibe", "beeuhpxccux8") or die(mysql_error());
  	mysql_select_db("gigalibe") or die(mysql_error());

  			$sql = "INSERT INTO comments (userid, commentdate, message, parentid) VALUES ($userid, $comment_date, '$comment_text', $parentid);";

        	mysql_query($sql) Or die(mysql_error());

        	$sql_get = "SELECT * FROM comments";
            $result = mysql_query($sql_get);
                    if   (mysql_num_rows($result) > 0){
                        $rows = array();
                        while($row =  mysql_fetch_assoc($result)){
                            $rows_ID[$row['id']][] = $row;
                            $rows[$row['parentid']][$row['id']] =  $row;
                        }
                    }
                    function build_tree($rows,$parent_id){
                        if(is_array($rows) and isset($rows[$parent_id])){
                            $tree = '<ul>';
                                foreach($rows[$parent_id] as $row){
                                    $date = date("Y-m-d | h:i:sa", $row["commentdate"]);
                                    $img = "<img src='https://graph.facebook.com/" . $row["userid"]. "/picture'>";
                                    $tree .= '<li class="parent_'.$row['id'].'">'.$img.'<span class="date">( '.$date.' )</span><span class="message"> '.$row['message'].'</span><br>';
                                    $tree .= '<span class="answer" data-parentid='.$row['id'].'>Ответить</span>';
                                    $tree .=  build_tree($rows,$row['id']);
                                    $tree .= '</li>';
                                }
                            $tree .= '</ul>';
                        }
                        else return null;
                        return $tree;
                    }
                    echo build_tree($rows,0);
  	mysql_close ();