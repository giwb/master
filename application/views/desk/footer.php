<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>한국여행</strong>. All Rights Reserved.<br>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="modal-message mt-3"></p>
                </div>
                <div class="modal-footer">
                    <form id="formModal" method="post">
                    <input type="hidden" name="idx">
                    <input type="hidden" name="value">
                    </form>
                    <button type="button" class="btn btn-modal-submit">확인</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
