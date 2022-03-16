<h2 class="text-center mt-5 mb-3"><?php echo $title;?></h2>
<div class="card">
    <div class="card-header">
        <a class="btn btn-outline-info float-right" href="<?php echo base_url('project/');?>"> 
            View All Projects
        </a>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('errors')) {?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php } ?>
 
        <form action="<?php echo base_url('project/update/' . $project->id);?>" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?php echo $project->name;?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    class="form-control"
                    id="description"
                    rows="3"
                    name="description"><?php echo $project->description;?></textarea>
            </div>
          
            <button type="submit" class="btn btn-outline-primary">Save Project</button>
        </form>
       
    </div>
</div>