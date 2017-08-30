<?php
error_reporting(1);
if (isset($_GET['id'])) {
    $client_id = $_GET['id'];
    ?>
    <!DOCTYPE html>
    <html>	
        <head>
            <title>Admin</title>

            <!-- Fonts -->
            <link rel="stylesheet" href="//suite.social/assets/font-awesome/css/font-awesome.min.css">

            <!-- bootstrap css -->
            <link rel="stylesheet" type="text/css" href="assests/bootstrap/css/bootstrap.min.css">
            <!-- datatables css -->
            <link rel="stylesheet" type="text/css" href="assests/datatables/datatables.min.css">
            <script type="text/javascript" src="assests/jquery/jquery.min.js"></script>
            <!-- bootstrap js -->
            <script type="text/javascript" src="assests/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript">
                function removeuser(id) {
                    var idd = id;

                    $.ajax
                            ({
                                url: 'deleteuser.php',
                                data: {"id": idd},
                                type: 'post',
                                success: function (result) {
                                    return true;
                                }
                            });

                }
            </script>

            <style type="text/css">

                body {
                    color: #fff;
                    background-color: #1f1f1f;
                }

                .page-header {
                    border-bottom: 1px solid #616161;				
                }

                .table-bordered {
                    border: 1px solid #616161;
                }

                .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                    border-bottom: 1px solid #616161;
                }

                .table-bordered>tbody>tr>td {
                    border: 1px solid #616161;
                }

                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                    border-top: 1px solid #616161;
                }	


                .table-striped>tbody>tr:nth-of-type(odd) {
                    background-color: #303030;
                }			

            </style>		

        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <center><h1 class="page-header">Users who shared</h1></center>
                        <div class="removeMessages"></div>
                        <!--<button class="btn btn-default pull pull-right" data-toggle="modal" data-target="#addMember"
                                id="addMemberModalBtn">
                            <span class="fa fa-user-plus"></span> Add Member
                        </button>-->
                        <a href="downloadcsv.php?id=<?php echo $client_id; ?>"><button class="btn btn-default pull pull-right">
                                <span class="fa fa-download"></span> Download CSV
                            </button></a>

                        <br/> <br/> <br/>
                        <table class="table table-striped" id="manageMemberTable">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Social ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <!--<th>Contact</th>-->
                                    <th>Gender</th>
                                    <th>Location</th>
                                    <th>Email</th>
                                    <th>No. Of Share</th>
                                    <th>IP Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            $i = 1;
                            foreach (glob('../' . $client_id . '_*.json') as $file) {
                                //get visitor count and ip address
                                $ex = explode('_', $file);
                                $exp = end($ex);
                                $explo = explode('.', $exp);
                                $exploded = $explo['0'];
                                //echo $exploded;
                                $file_visitor = 'visitor_' . $client_id . '_' . $exploded . '.txt';
                                $visitor_count = file_get_contents('../' . $file_visitor); //visitor count

                                $file_ip = $client_id . '_' . $exploded . '.txt';
                                $cont = file_get_contents('../' . $file_ip);
                                $ips = explode(';', $cont);
                                $ip = $ips['0']; //IP Address

                                foreach (file($file) as $line) {
                                    $json = json_decode($line, TRUE); {
                                        $full_name = $network = $location = $gender = $age = $contact = $email = '';

                                        $network = isset($json['network']) ? $json['network'] : "";

                                        if (isset($json['name']['familyName'])) {
                                            $full_name = $json['displayName'];
                                        } else {
                                            $full_name = $json['name'];
                                        }
                                        if (!isset($json['profile_image_url']) && !isset($json['image']['url'])) {
                                            $img = $json['avatar'];
                                            $image = str_replace("\/", "/", $img);
                                        } else if (!isset($json['profile_image_url']) && isset($json['image']['url'])) {
                                            $image = $json['image']['url'];
                                        } else if (isset($json['profile_image_url']) && !isset($json['image']['url'])) {
                                            $image = $json['profile_image_url'];
                                        }


                                        if ($network == 'facebook') {
                                            $full_name = $json['name'];
                                            $email = $json['email'];
                                            $birthday = isset($json['birthday']) ? $json['birthday'] : "";
                                            if (!empty($birthday)) {
                                                $birthyear = new DateTime($birthday);
                                                $currentYear = new DateTime('today');
                                                $age = $birthyear->diff($currentYear)->y;
                                            }
                                            $gender = strtolower($json['gender']);
                                            if (isset($json['location']) && is_array($json['location'])) {
                                                $location = $json['location']['name'];
                                            }
                                        }
                                        if ($network == 'twitter') {
                                            $full_name = $json['name'];
                                            $location = $json['location'];
                                            $gender = $json['gender'];
                                            $email = $json['email'];
                                            if(isset($json['profile_image_url']) && !empty($json['profile_image_url'])){
                                                $image=$json['profile_image_url'];
                                                $image= str_replace('_normal', '', $image);
                                            }
                                        }
                                        if ($network == 'googleplus') {
                                            $full_name = $json['displayName'];
                                            $email = $json['email'];
                                            $gender = strtolower($json['gender']);
                                            if (isset($json['placesLived']) && is_array($json['placesLived'])) {
                                                $location = end($json['placesLived'])['value'];
                                            }
                                            if(isset($json['ageRange']) && is_array($json['ageRange'])){
                                                $age=$json['ageRange']['min'];
                                            }
                                        }
                                        if ($network == 'linkedin') {
                                            $full_name = $json['formattedName'];
                                            $email = $json['email'];
                                            $location = isset($json['location']['name']) ? $json['location']['name'] : "";
                                            $image = isset($json['profile_picture']) ? $json['profile_picture'] : "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $json['id']."(".$json['network'].")"; ?></td>
                                            <td><img src="<?php echo $image; ?>" style="width: 40px"> </td>
                                            <td><?= $full_name ?></td>
                                            <td><?= $age ?></td>
                                            <!--<td><?php //$contact ?></td>-->
                                            <td><?= $gender ?></td>
                                            <td><?= $location ?></td>
                                            <td><?= $email ?></td>
                                            <td><?php echo $visitor_count; ?></td>
                                            <td><?php echo $ip; ?></td>
                                            <td data-id="<?php echo $json['id']; ?>"><a href="deleteuser.php?id=<?php echo $json['id']; ?>&cid=<?php echo $client_id; ?>"><button class="delete_button">Delete</button></a></td>

                                        </tr>

                                        <?php
                                    }
                                }
                                $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- add modal -->
            <div class="modal fade" tabindex="-1" role="dialog" id="addMember">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><span class="fa fa-user-plus"></span> Add User</h4>
                        </div>
                        <form class="form-horizontal" action="php_action/create.php" method="POST" id="createMemberForm">
                            <div class="modal-body">
                                <div class="messages"></div>
                                <div class="form-group"> <!--/here teh addclass has-error will appear -->
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                        <!-- here the text will apper  -->
                                    </div>
                                </div>
                                <div class="form-group"> <!--/here teh addclass has-error will appear -->
                                    <label for="fb_link" class="col-sm-3 control-label">User Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fb_link" name="fb_link"
                                               placeholder="Facebook User Name">
                                        <!-- here the text will apper  -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="address" name="address"
                                               placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contact" class="col-sm-3 control-label">Contact</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="contact" name="contact"
                                               placeholder="Contact">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="active" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="active" id="active">
                                            <option value="1">Arrived</option>
                                            <option value="2">Pending</option>
                                            <option value="3">Exited</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="notes" class="col-sm-3 control-label">Notes</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="notes" name="notes"
                                                  placeholder="Enter Notes.."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span class="gif hide">Saving....</span>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- /add modal -->

            <!-- remove modal -->
            <div class="modal fade" tabindex="-1" role="dialog" id="removeMemberModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><span class="fa fa-trash"></span> Remove Member</h4>
                        </div>
                        <div class="modal-body">
                            <p>Do you really want to remove ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="removeBtn">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- /remove modal -->

            <!-- edit modal -->
            <div class="modal fade" tabindex="-1" role="dialog" id="editMemberModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><span class="fa fa-edit"></span> Edit Member</h4>
                        </div>

                        <form class="form-horizontal" action="php_action/update.php" method="POST" id="updateMemberForm">

                            <div class="modal-body">

                                <div class="edit-messages"></div>

                                <div class="form-group"> <!--/here teh addclass has-error will appear -->
                                    <label for="editName" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="editName" name="editName"
                                               placeholder="Name">
                                        <!-- here the text will apper  -->
                                    </div>
                                </div>
                                <div class="form-group"> <!--/here teh addclass has-error will appear -->
                                    <label for="editfb_link" class="col-sm-3 control-label">User Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="editfb_link" name="editfb_link"
                                               placeholder="Facebook User Name">
                                        <!-- here the text will apper  -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editAddress" class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="editAddress" name="editAddress"
                                               placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editContact" class="col-sm-3 control-label">Contact</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="editContact" name="editContact"
                                               placeholder="Contact">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editActive" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="editActive" id="editActive">
                                            <option value="1">Active</option>
                                            <option value="2">Pending</option>
                                            <option value="3">Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="notes" class="col-sm-3 control-label">Notes</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="editnotes" name="editNotes"
                                                  placeholder="Enter Notes.."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer editMemberModal">
                                <span class="gif hide">Saving....</span>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- /edit modal -->

            <!-- jquery plugin -->
            <script type="text/javascript" src="assests/jquery/jquery.min.js"></script>
            <!-- bootstrap js -->
            <script type="text/javascript" src="assests/bootstrap/js/bootstrap.min.js"></script>
            <!-- datatables js -->
            <script type="text/javascript" src="assests/datatables/datatables.min.js"></script>
            <!-- include custom index.js
            <script type="text/javascript" src="custom/js/index.js"></script>-->
        </body>
    </html>
    <?php
}
?>