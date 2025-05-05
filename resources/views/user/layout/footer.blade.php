<footer class="site-footer text-center">
    &copy; {{ date('Y') }} PHRMO - Nueva Vizcaya. All rights reserved. <br>
    Developed By: 
    <a href="#" data-bs-toggle="modal" data-bs-target="#gapModal" style="text-decoration: underline;">
        GAP
    </a>
</footer>
<div class="modal fade" id="gapModal" tabindex="-1" aria-labelledby="gapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md"> <!-- made larger -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="gapModalLabel">DEVELOPMENT TEAM</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: whitesmoke;">
                <div id="gapCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        <!-- Slide 1 -->
                        <div class="carousel-item active text-center">
                            <img src="{{ asset('images/arjay.jpg') }}" class="rounded-circle mb-3" alt="Team Member 1" width="150" height="150">
                            <h5>Arjay Ordinario</h5>
                            <p class="text-muted">Lead Developer / Full-Stack Developer</p>
                        </div>

                        <!-- Slide 2 -->
                        <div class="carousel-item text-center">
                            <img src="{{ asset('images/paolo.jpg') }}" class="rounded-circle mb-3" alt="Team Member 2" width="150" height="150">
                            <h5>Jan Paolo Aduca</h5>
                            <p class="text-muted">UI/UX Designer / Front-End Developer</p>
                        </div>

                        <!-- Slide 3 -->
                        <div class="carousel-item text-center">
                            <img src="{{ asset('images/generose.jpg') }}" class="rounded-circle mb-3" alt="Team Member 3" width="150" height="150">
                            <h5>Generose Bugtong</h5>
                            <p class="text-muted">Documentarian</p>
                        </div>

                    </div>
                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#gapCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#gapCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

