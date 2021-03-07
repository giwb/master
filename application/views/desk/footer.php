<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>한국여행</strong>. All Rights Reserved.<br>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Survey Delete Modal -->
    <div class="modal fade" id="deleteArticleModal" tabindex="-1" role="dialog" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="modal-message mt-3">정말로 삭제하시겠습니까?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteArticle" method="post" action="/desk/article_delete">
                    <input type="hidden" name="idx">
                    </form>
                    <button type="button" class="btn btn-danger btn-delete-article">삭제합니다</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
