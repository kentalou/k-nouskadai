// ============================
// Global Variables & Functions
// ============================

// 現在の検索ページ番号（グローバル）
let currentSearchPage = 1;
let currentSortBy = 'id';      // 初期ソートカラム
let currentSortOrder = 'asc';  // 初期ソート順

// Ajax 検索関数（ソートパラメータ追加済み）
function doSearch(page, sortBy = currentSortBy, sortOrder = currentSortOrder) {
    currentSearchPage = page;
    currentSortBy = sortBy;
    currentSortOrder = sortOrder;

    let keyword    = $('input[name="keyword"]').val();
    let company_id = $('select[name="company_id"]').val();
    let price_min  = $('input[name="price_min"]').val();
    let price_max  = $('input[name="price_max"]').val();
    let stock_min  = $('input[name="stock_min"]').val();
    let stock_max  = $('input[name="stock_max"]').val();

    $.ajax({
        url: searchUrl + '?page=' + page, // searchUrl は親テンプレートで定義
        type: 'GET',
        data: {
            keyword: keyword,
            company_id: company_id,
            price_min: price_min,
            price_max: price_max,
            stock_min: stock_min,
            stock_max: stock_max,
            sort_by: sortBy,        // ソート対象カラム
            sort_order: sortOrder   // ソート順
        },
        dataType: 'json',
        success: function(response) {
            if (response.html && response.pagination) {
                // tbody部分 (#product-table) を更新（既存データを完全に置換）
                $('#product-table').html(response.html);
                // ページネーション部分 (#pagination-container) を更新
                $('#pagination-container').html(response.pagination);
            }
        },
        error: function() {
            alert('検索に失敗しました。');
        }
    });
}

// ============================
// 検索機能の初期化
// ============================
function initSearchFunction() {
    // 検索ボタン押下時に1ページ目検索
    $('.search-btn').click(function(e) {
        e.preventDefault();
        doSearch(1);
    });

    // ページネーションリンク押下時の検索
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = url.split('page=')[1];
        doSearch(page);
    });
}

// ============================
// ソート機能の初期化（tablesorter を使用しないサーバーサイドソート実装）
// ============================
function initSortFunction() {
    // ヘッダー（.sort-header）クリックイベントをバインド
    $(document).on('click', '.sort-header', function(e) {
        e.preventDefault();
        let newSortBy = $(this).data('column');
        // 同じカラムの場合は昇順/降順をトグル、違うカラムなら昇順に設定
        let newSortOrder = (currentSortBy === newSortBy) ? 
                           ((currentSortOrder === 'asc') ? 'desc' : 'asc') : 'asc';

        // ヘッダーのソート状態インジケーター更新
        updateSortIndicators(newSortBy, newSortOrder);
        // ソート条件を更新し、1ページ目から再検索
        doSearch(1, newSortBy, newSortOrder);
    });

    // Enterキーでクリックと同じ動作をするようにする	
    $(document).on('keydown', '.sort-header', function(e) {	
        if (e.key === "Enter") {	
        $(this).click();	
        }	
    });
}

// ヘッダーのソート状態インジケーター更新（CSSで sort-asc, sort-desc をスタイル設定してください）
function updateSortIndicators(sortBy, sortOrder) {
    // 全ての.sort-headerから以前の矢印表示とクラスを削除
    $('.sort-header').removeClass('sort-asc sort-desc');
    $('.sort-header').find('.sort-indicator').remove();
    // 対象のヘッダーにクラスと矢印表示を追加
    $('.sort-header').each(function() {
        if ($(this).data('column') === sortBy) {
            $(this).addClass(sortOrder === 'asc' ? 'sort-asc' : 'sort-desc');
            // 昇順なら上矢印、降順なら下矢印を表示
            var arrow = sortOrder === 'asc' ? ' ▲' : ' ▼';
            $(this).append('<span class="sort-indicator">' + arrow + '</span>');
        }
    });
}

// ============================
// 非同期削除処理
// ============================
$(document).on('click', '.delete-button', function(e) {
    e.preventDefault();
    const button = $(this);

    Swal.fire({
        title: '本当に削除しますか？',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'はい、削除します',
        cancelButtonText: 'キャンセル'
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = button.data('delete-url');
            const token = button.data('token');

            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: token,
                    _method: 'DELETE'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: configMessage.delete_success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // 削除成功後、現在の検索条件のページで再検索
                        doSearch(currentSearchPage);
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: configMessage.delete_error
                    });
                }
            });
        }
    });
});

// ============================
// DOMContentLoaded 時の初期化処理
// ============================
document.addEventListener('DOMContentLoaded', function () {
    initSearchFunction();
    initSortFunction();
});
