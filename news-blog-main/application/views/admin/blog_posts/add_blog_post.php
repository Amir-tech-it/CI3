<?php $this->load->view('admin/dashboard/header'); ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <h2 class="blog-title">Add a Blog Post </h2>

      <form id="add-blog" enctype="multipart/form-data">
    		    
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" name="title"/>
        </div>
        <div class="all_errors title_error"></div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea type="text" class="form-control" name="description" id="description"></textarea>
        </div>
        <script>
          ClassicEditor
            .create( document.querySelector( '#description' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );
        </script>
        <div class="description_error all_errors"></div>


        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control" name="image"/>
        </div>
        <div class="image_error all_errors"></div>
    		    
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Add Post</button>
        </div>
    		    
      </form>

    </main>
  </div>
</div>

<?php $this->load->view('admin/dashboard/footer'); ?>


