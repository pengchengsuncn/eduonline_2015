<?php require_once(dirname(__FILE__).'/include/config.inc.php');

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(); ?>
<link type="text/css" rel="stylesheet" href="style/index.css">
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="content">
      <div class="banner">
        <div class="hd">
          <ul>
          </ul>
        </div>
        <div class="bd">
          <ul>
            <?php
			$dosql->Execute("SELECT * FROM `#@__admanage` WHERE classid=1 AND checkinfo='true' ORDER BY orderid DESC");
			while($row=$dosql->GetArray()){
			?>
            <li><a href="<?php echo $row['linkurl'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" alt="<?php echo $row['title'];?>" /></a></li>
            <?php
			}
			?>
          </ul>
        </div>
      </div>
      <div class="tab">
        <div class="hd">
          <ul>
            <li>资讯中心</li>
            <li>最新课程</li>
            <li>最热资料</li>
            <li>家长教育</li>
          </ul>
        </div>
        <div class="bd">
          <ul>
            <li>
              <?php
			  $dosql->Execute("SELECT * FROM `#@__infolist` WHERE classid=5 AND delstate='' AND checkinfo='true' ORDER BY orderid DESC LIMIT 0,8");
			  $i=1;
			  while($row=$dosql->GetArray()){
			  ?>
              <div class="news-item">
                <div class="news-item-title">
                  <p><small><?php echo $i;?></small><a href="article_show.php?cid=5&id=<?php echo $row['id'];?>" class="ftblack" title="<?php echo $row['title'];?>"><?php echo ReStrLen($row['title'],10);?></a></p>
                  <p><?php echo ReStrLen($row['description'],28);?></p>
                </div>
                <div class="news-item-pic"><img src="<?php echo $row['picurl'];?>" width="95" height="65" /></div>
              </div>
              <?php
			      $i++;
			  }
			  ?>
            </li>
          </ul>
          <ul>
            <li>
              <?php
			  $dosql->Execute("SELECT * FROM `#@__course` WHERE checkinfo='true' AND is_delete='false' AND picurl!='' ORDER BY posttime DESC LIMIT 0,8");
			  $i=1;
			  while($row = $dosql->GetArray()){
			  ?>
              <div class="news-item">
                <div class="news-item-title">
                  <p><small><?php echo $i;?></small><a href="course.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row['title'],10);?></a></p>
                  <p><?php echo ReStrLen($row['description'],28);?></p>
                </div>
                <div class="news-item-pic"><img src="<?php echo $row['picurl'];?>" width="95" height="65" /></div>
              </div>
              <?php
			      $i++;
			  }
			  ?>
            </li>
          </ul>
          <ul>
            <li>
              <?php
			  $dosql->Execute("SELECT * FROM `#@__infoclass` WHERE parentid = 9 AND checkinfo = 'true' ORDER BY orderid ASC LIMIT 0,2");
			  while($row = $dosql->GetArray()){
				  $i=$row['id'];
			  ?>
              <div class="news-warp fl">
                <dl>
                  <dt><a href="article.php?cid=<?php echo $i;?>" class="ftred"><?php echo ReStrLen($row['classname'],16);?></a></dt>
                  <?php
				  $dosql->Execute("SELECT * FROM `#@__infolist` WHERE classid=$i AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,9",$i);
			      while($row2 = $dosql->GetArray($i)){
				  ?>
                  <dd><a href="article_show.php?cid=<?php echo $row2['classid'];?>&id=<?php echo $row2['id'];?>" class="ftblack" title="<?php echo $row2['title'];?>"><?php echo ReStrLen($row2['title'],20);?></a></dd>
                  <?php
				  }
				  ?>
                </dl>
              </div>
              <?php
			  }
			  ?>
            </li>
          </ul>
          <ul>
            <li>
              <?php
			  $dosql->Execute("SELECT * FROM `#@__infoclass` WHERE parentid = 6 AND checkinfo = 'true' ORDER BY orderid ASC LIMIT 0,2");
			  while($row = $dosql->GetArray()){
				  $i=$row['id'];
			  ?>
              <div class="news-warp fl">
                <dl>
                  <dt><a href="article.php?cid=<?php echo $i;?>" class="ftred"><?php echo ReStrLen($row['classname'],16);?></a></dt>
                  <?php
				  $dosql->Execute("SELECT * FROM `#@__infolist` WHERE classid=$i AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,9",$i);
			      while($row2 = $dosql->GetArray($i)){
				  ?>
                  <dd><a href="article_show.php?cid=<?php echo $row2['classid'];?>&id=<?php echo $row2['id'];?>" class="ftblack" title="<?php echo $row2['title'];?>"><?php echo ReStrLen($row2['title'],20);?></a></dd>
                  <?php
				  }
				  ?>
                </dl>
              </div>
              <?php
			  }
			  ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="rightbar fr">
      <section class="rbar-1">
        <h3>登陆/注册</h3>
        <div class="rbar-login">
          <script type="text/javascript" src="templates/default/js/member.js"></script>
          <form id="form" method="post" action="member.php?a=login" onsubmit="return CheckLog();">
            <p>
              <input type="text" name="username" id="username" placeholder="请输入用户名/邮箱地址" class="ipt">
            </p>
            <p>
              <input type="password" name="password" id="password" placeholder="请输入密码" class="ipt">
            </p>
            <p>
              <label>
                <input id="autologin" type="checkbox" value="1" name="autologin">
                &nbsp;记住我</label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="member.php?c=findpwd" class="ftblack">找回密码</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="member.php?c=login" class="ftblack">立即注册</a></p>
            <p>
              <input type="submit" name="" value="登陆" class="ipt sub">
            </p>
          </form>
          <div class="other-login">其他登陆方式：
            <a href="data/api/oauth/connect.php?method=weibo_token" target="_blank"><button type="button" class="other-1" title="微博登录"></button></a>
            <a href="data/api/oauth/connect.php?method=qq_token" target="_blank"><button type="button" class="other-2" title="QQ登录"></button></a>
            <!--<button type="button" class="other-3"></button>-->
          </div>
        </div>
      </section>
      <section class="rbar-2">
        <h3>便捷通道</h3>
        <div class="rbar-conv">
          <ul>
            <li><a href="javascript:;" class="conv-1"><img src="images/conv-1.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-2"><img src="images/conv-2.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-3"><img src="images/conv-3.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-4"><img src="images/conv-4.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-5"><img src="images/conv-5.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-6"><img src="images/conv-6.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-7"><img src="images/conv-7.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-8"><img src="images/conv-8.png" alt="**"/></a></li>
            <li><a href="javascript:;" class="conv-9"><img src="images/conv-9.png" alt="**"/></a></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
</div>
<div class="pagebody">
  <div class="c"> 
    <!--  我是高中生-模块  -->
    <section class="section-col fl">
      <h2 class="ftred"><span class="fl">我是高中生</span><a href="course_more.php" class="col-more fr"></a></h2>
      <div class="section-1 con">
        <div class="hd">
          <ul>
            <li>全部</li>
			<?php
			//获取高中一级分类
			$dosql->Execute("SELECT id FROM #@__course_type WHERE classname='高中' AND checkinfo='true' AND parentid=0 ORDER BY orderid ASC");
			$gfir_arr = array();
			while($row = $dosql->GetArray()){
				$gfir_arr[] = $row['id'];
			}
			$gfir_type = implode(',',$gfir_arr);
			//获取二级分类
			$dosql->Execute("SELECT * FROM #@__course_type WHERE parentid IN($gfir_type) AND checkinfo='true' ORDER BY orderid ASC");
			$gsec_arr = array();
			while($row = $dosql->GetArray()){
				$gsec_arr[] = $row['id'];
			?>
            <li><?php echo $row['classname'];?></li>
            <?php
			}
			?>
          </ul>
        </div>
        <div class="bd">
          <ul>
            <li>
              <ul>
                <?php
				$gsec_type = implode(',',$gsec_arr);
				if($gsec_type!=''){
				$dosql->Execute("SELECT * FROM #@__course WHERE classid IN($gsec_type) AND checkinfo='true' AND is_recommend='true' ORDER BY orderid DESC LIMIT 0,8");
				while($row = $dosql->GetArray()){
					//获取授课老师
					$t = $dosql->GetOne('SELECT * FROM #@__member WHERE id='.$row['uid']);
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pic"><a href="course.php?id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" /></a></div>
                    <div class="col-warp-msg">
                      <p><a href="course.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row['title'],13);?></a></p>
                      <p><span class="fl">授课老师：<?php if(empty($t['cnname'])) echo $t['username']; else echo $t['cnname'];?></span><!--<span class="fr">试听：485 </span>--></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				}
				?>
              </ul>
            </li>
          </ul>
          <?php
		  $dosql->Execute("SELECT * FROM #@__course_type WHERE parentid IN($gfir_type) AND checkinfo='true' ORDER BY orderid ASC");
		  while($row = $dosql->GetArray()){
			  $i = $row['id'];
		  ?>
          <ul>
            <li>
              <ul>
                <?php
				$dosql->Execute("SELECT * FROM #@__course WHERE classid=$i AND checkinfo='true' AND is_recommend='true' ORDER BY orderid DESC LIMIT 0,8",$i);
				while($row2 = $dosql->GetArray($i)){
					//获取授课老师
					$t = $dosql->GetOne('SELECT * FROM #@__member WHERE id='.$row2['uid']);
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pic"><a href="course.php?id=<?php echo $row2['id'];?>" target="_blank"><img src="<?php echo $row2['picurl'];?>" /></a></div>
                    <div class="col-warp-msg">
                      <p><a href="course.php?id=<?php echo $row2['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row2['title'],13);?></a></p>
                      <p><span class="fl">授课老师：<?php if(empty($t['cnname'])) echo $t['username']; else echo $t['cnname'];?></span><!--<span class="fr">试听：485 </span>--></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				?>
              </ul>
            </li>
          </ul>
          <?php
		  }
		  ?>
        </div>
      </div>
    </section>
    <!--  我是初中生-模块  -->
    <section class="section-col fl">
      <h2 class="ftred"><span class="fl">我是初中生</span><a href="course_more.php" class="col-more fr"></a></h2>
      <div class="section-1 con">
        <!--<div class="hd">
          <ul>
            <li>全部</li>
            <li>数学</li>
            <li>化学</li>
            <li>物理</li>
            <li>地理</li>
          </ul>
        </div>-->
        <div class="bd">
          <ul>
            <li>
              <ul>
                <?php
				//获取初中一级分类
				$dosql->Execute("SELECT id FROM #@__course_type WHERE (classname='七年级' OR classname='八年级' OR classname='九年级') AND checkinfo='true' AND parentid=0 ORDER BY orderid ASC");
				$fir_arr = array();
				while($row = $dosql->GetArray()){
					$fir_arr[] = $row['id'];
				}
				$fir_type = implode(',',$fir_arr);
				//获取二级分类
				$dosql->Execute("SELECT id FROM #@__course_type WHERE parentid IN($fir_type) AND checkinfo='true' ORDER BY orderid ASC");
				$sec_arr = array();
				while($row = $dosql->GetArray()){
					$sec_arr[] = $row['id'];
				}
				$sec_type = implode(',',$sec_arr);
				if($sec_type!=''){
				//获取推荐课程
				$dosql->Execute("SELECT * FROM #@__course WHERE classid IN($sec_type) AND checkinfo='true' AND is_recommend='true' ORDER BY orderid DESC LIMIT 0,8");
				while($row = $dosql->GetArray()){
					//获取授课老师
					$t = $dosql->GetOne('SELECT * FROM #@__member WHERE id='.$row['uid']);
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pic"><a href="course.php?id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" /></a></div>
                    <div class="col-warp-msg">
                      <p><a href="course.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row['title'],13);?></a></p>
                      <p><span class="fl">授课老师：<?php if(empty($t['cnname'])) echo $t['username']; else echo $t['cnname'];?></span><!--<span class="fr">试听：485 </span>--></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				}
				?>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <!--  家长专区-模块  -->
    <section class="section-col fl">
      <h2 class="ftred"><span class="fl">家长专区</span><a href="article.php?cid=12" class="col-more fr"></a></h2>
      <div class="section-1 con">
        <div class="hd">
          <ul>
            <li>全部</li>
            <?php
			$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE parentid = 12 AND checkinfo = 'true' ORDER BY orderid ASC LIMIT 0,5");
			while($row = $dosql->GetArray()){
			?>
            <li><?php echo $row['classname'];?></li>
            <?php
			}
			?>
          </ul>
        </div>
        <div class="bd">
          <ul>
            <li>
              <ul>
                <?php
				$dosql->Execute("SELECT * FROM `#@__infolist` WHERE (classid=12 OR parentstr LIKE '%,12,%') AND delstate='' AND checkinfo=true AND flag LIKE '%h%' ORDER BY orderid DESC LIMIT 0,8");
				while($row = $dosql->GetArray()){
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pic"><a href="article_show.php?cid=<?php echo $row['classid'];?>&id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" /></a></div>
                    <div class="col-warp-msg">
                      <p><a href="article_show.php?cid=<?php echo $row['classid'];?>&id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row['title'],13);?></a></p>
                      <p><?php echo ReStrLen($row['description'],15);?></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				?>
              </ul>
            </li>
          </ul>
          <?php
		  $dosql->Execute("SELECT * FROM `#@__infoclass` WHERE parentid = 12 AND checkinfo = 'true' ORDER BY orderid ASC LIMIT 0,5");
		  while($row = $dosql->GetArray()){
			  $i=$row['id'];
		  ?>
          <ul>
            <li>
              <ul>
                <?php
				$dosql->Execute("SELECT * FROM `#@__infolist` WHERE (classid=$i OR parentstr LIKE '%,$i,%') AND delstate='' AND checkinfo=true AND flag LIKE '%c%' ORDER BY orderid DESC LIMIT 0,8",$i);
				while($row2 = $dosql->GetArray($i)){
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pic"><a href="article_show.php?cid=<?php echo $row2['classid'];?>&id=<?php echo $row2['id'];?>" target="_blank"><img src="<?php echo $row2['picurl'];?>" /></a></div>
                    <div class="col-warp-msg">
                      <p><a href="article_show.php?cid=<?php echo $row2['classid'];?>&id=<?php echo $row2['id'];?>" target="_blank" class="ftblack"><?php echo ReStrLen($row2['title'],13);?></a></p>
                      <p><?php echo ReStrLen($row2['description'],15);?></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				?>
              </ul>
            </li>
          </ul>
          <?php
		  }
		  ?>
        </div>
      </div>
    </section>
    <!--  名师推荐-模块  -->
    <section class="section-col fl">
      <h2 class="ftred"><span class="fl">名师推荐</span><a href="teacher.php" class="col-more fr"></a></h2>
      <div class="section-1 con">
        <div class="bd">
          <ul>
            <li>
              <ul>
                <?php
				$dosql->Execute("SELECT m.*,r.remark FROM `#@__member` AS m LEFT JOIN `#@__member_recommend` AS r ON m.id=r.uid WHERE m.is_teacher='true' AND m.cnname!='' ORDER BY r.orderid DESC,m.id DESC LIMIT 0,4");
				while($row=$dosql->GetArray()){
				?>
                <li>
                  <div class="section-col-warp">
                    <div class="col-warp-pho"><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank"><img src="data/avatar/index.php?uid=<?php echo $row['id'];?>&size=big" /></a></div>
                    <div class="col-warp-title">
                      <p><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank" class="ftred"><?php if(!empty($row['cnname'])) echo $row['cnname']; else echo $row['username'];?></a></p>
                      <p>讲师</p>
                    </div>
                    <div class="col-warp-desc">
                      <p><?php echo ReStrLen($row['remark'],25);?></p>
                    </div>
                  </div>
                </li>
                <?php
				}
				?>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
