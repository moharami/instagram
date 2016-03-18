
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="user-info-wrapper">
      <div class="profile-wrapper">
        <?php 
        echo $this->Html->image('/webarch/img/profiles/avatar.jpg', array('alt'=> 'altText'));
         ?>
      </div>
      <div class="user-info">
        <div class="greeting">Instagram</div>
        <div class="username">          
          <span class="semi-bold"></span>
        </div>
        <div class="status">
          
          <a href="#">
            <div class="status-icon green"></div>
            
          </a>
        </div>
      </div>
    </div>
    <!-- END MINI-PROFILE -->

    <!-- BEGIN SIDEBAR MENU -->

    <ul>
      <li>
        
      </li>
      <li>
        
      </li>
      <li class="">
        <a href="javascript:;">
          <i class="icon-custom-ui"></i>
          <span class="title">User</span>
          <span class="arrow "></span>
        </a>
        <ul class="sub-menu">
          <li >
          <?php 
            echo $this->Html->link("do follow", array('controller'=>'Users', 'action'=>'do_follow'));
           ?>
          </li>
          <li >
            <?php 
              echo $this->Html->link("Following", array('controller'=>'Posts', 'action'=>'fetchallarticles',1));
             ?>
          </li>
        </ul>
      </li>
      <li class="">
        <a href="javascript:;">
          <i class="icon-custom-ui"></i>
          <span class="title">Actor</span>
          <span class="arrow "></span>
        </a>
        <ul class="sub-menu">
          <li >
          <?php 
            echo $this->Html->link("add", array('controller'=>'Users', 'action'=>'add_actor'));
           ?>
          </li>          
          <li >
          <?php 
            echo $this->Html->link("reset", array('controller'=>'Users', 'action'=>'reset_all'));
           ?>
          </li>
          <li >
          <?php 
            echo $this->Html->link("Update All", array('controller'=>'Users', 'action'=>'update_all'));
           ?>
          </li> 
        </ul>
      </li>
      <!-- ************** -->
      <li class="">
        <a href="javascript:;">
          <i class="icon-custom-ui"></i>
          <span class="title">follower</span>
          <span class="arrow "></span>
        </a>
        <ul class="sub-menu">
          <li>
          <?php 
            echo $this->Html->link("count", array('controller'=>'Users', 'action'=>'follower'));
           ?>
          </li>
          <li>
          <?php 
            echo $this->Html->link("Track", array('controller'=>'Followers', 'action'=>'index'));
           ?>
          </li>                    
        </ul>
      </li>
      <!-- ************** -->

    </ul>

    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU --> </div>

  <!-- END SIDEBAR -->