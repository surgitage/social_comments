<?php
	mysql_connect("localhost", "gigalibe", "beeuhpxccux8") or die(mysql_error());
  mysql_select_db("gigalibe") or die(mysql_error());

                    mysql_query("CREATE TABLE IF NOT EXISTS comments(
                      id INT AUTO_INCREMENT,
                      userid BIGINT,
                      commentdate BIGINT,
                      message TEXT,
                      parentid INT DEFAULT 0,
                      PRIMARY KEY(id)
                    )") Or die(mysql_error());

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
?>