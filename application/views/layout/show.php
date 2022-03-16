<h2 class="text-center mt-5 mb-3"><?php echo $title;?></h2>
<div class="card">
    <div class="card-header">
        <a class="btn btn-outline-info float-right" href="<?php echo base_url('project/');?>"> 
            View All Projects
        </a>
    </div>
    <div class="card-body">
       <b class="text-muted">Name:</b>
       <p><?php echo $project->name;?></p>
       <b class="text-muted">Description:</b>
       <p><?php echo $project->description;?></p>
    </div>
</div>