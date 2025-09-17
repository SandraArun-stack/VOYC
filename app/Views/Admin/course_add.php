<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?= isset($course) ? 'Update Course' : 'Add Course'; ?></h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?= isset($course) ? 'Update Course' : 'Add Course'; ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div id="messageBox" class="alert alert-success" style="display:none;"></div>
                                </div>
                                <div class="card-block">
                                    <form id="createCourse" method="post" enctype="multipart/form-data" style="font-size:14px;">
                                        <!-- Course Name -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Course Name <span style="color:red;">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="course_name" class="form-control" 
                                                value="<?= isset($course) ? esc($course['course_name']) : '' ?>" 
                                                placeholder="Enter Course Name" required>
                                            </div>
                                        </div>

                                        <!-- Duration -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Duration (Months) <span style="color:red;">*</span></label>
                                            <div class="col-sm-6">
                                                <select name="duration" class="form-control" required>
                                                    <option value="">Select Duration</option>
                                                    <?php 
                                                    $months = [1,2,3,6,12,18,24];
                                                    foreach($months as $m): ?>
                                                        <option value="<?= $m ?>" <?= isset($course) && $course['duration']==$m ? 'selected' : '' ?>><?= $m ?> Month<?= $m>1 ? 's' : '' ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Course Description -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Course Description</label>
                                            <div class="col-sm-6">
                                                <textarea name="course_description" class="form-control" rows="3" placeholder="Enter Course Description"><?= isset($course) ? esc($course['course_description']) : '' ?></textarea>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="mb-3 text-left">Lesson Details</h5>

                                        <div id="lessonContainer">
                                            <!-- Lesson fields (repeatable) -->
                                            <?php 
                                            if(isset($course['lessons']) && !empty($course['lessons'])):
                                                foreach($course['lessons'] as $lesson): ?>
                                                    <div class="lesson-block mb-3">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Lesson Name <span style="color:red;">*</span></label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="lesson_name[]" class="form-control" value="<?= esc($lesson['lesson_name']) ?>" placeholder="Enter Lesson Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Lesson Description</label>
                                                            <div class="col-sm-6">
                                                                <textarea name="lesson_description[]" class="form-control" rows="2" placeholder="Enter Lesson Description"><?= esc($lesson['lesson_description']) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Lesson Video</label>
                                                            <div class="col-sm-6">
                                                                <input type="file" name="lesson_video[]" class="form-control-file">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                <?php endforeach; 
                                            else: ?>
                                                <div class="lesson-block mb-3">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Lesson Name <span style="color:red;">*</span></label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="lesson_name[]" class="form-control" placeholder="Enter Lesson Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Lesson Description</label>
                                                        <div class="col-sm-6">
                                                            <textarea name="lesson_description[]" class="form-control" rows="2" placeholder="Enter Lesson Description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Lesson Video</label>
                                                        <div class="col-sm-6">
                                                            <input type="file" name="lesson_video[]" class="form-control-file">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Add More Lesson Button -->
                                        <div class="form-group row">
                                            <div class="col-sm-6 offset-sm-2">
                                                <button type="button" class="btn btn-secondary" id="addLesson">+ Add More Lesson</button>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="row justify-content-center">
                                            <input type="hidden" name="course_id" value="<?= isset($course) ? esc($course['course_id']) : '' ?>">
                                            <div class="button-group">
                                                <button type="button" class="btn btn-secondary" style="font-size:14px;"
                                                onclick="window.location.href='<?= base_url('admin/courses'); ?>'">Discard</button>
                                                <button type="submit" class="btn btn-primary" style="font-size:14px;"><?= isset($course) ? 'Update' : 'Save'; ?></button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"></div>
        </div>
    </div>
</div>

