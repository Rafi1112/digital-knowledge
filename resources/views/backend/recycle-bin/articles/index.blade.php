@extends('backend.layouts.app')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <nav class="mb-3">
                    <ul class="breadcrumb breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="{{ route('menu.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Recycle Bin</li>
                        <li class="breadcrumb-item active"><a href="{{ route('trash.article.index') }}">Trashed Articles</a></li>
                    </ul>
                </nav>
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Trashed Articles</h3>
                            <div class="nk-block-des text-soft">
                                <p>Total articles in recycle bin : {{ $articles->total() }} articles</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="more-options" style="display: none;">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <div class="form-control-wrap">
                                                <form action="{{ route('search.trash.article') }}" method="get" autocomplete="off">
                                                    <div class="form-icon form-icon-right">
                                                        <button type="submit" class="btn btn-sm">
                                                            <em class="icon ni ni-search"></em>
                                                        </button>
                                                    </div>
                                                    <input type="text" class="form-control" name="query" id="default-04" placeholder="Search by title">
                                                </form>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="drodown">
                                                <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#"><span>Last 30 Days</span></a></li>
                                                        <li><a href="#"><span>Last 6 Months</span></a></li>
                                                        <li><a href="#"><span>Last 1 Years</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (session('success'))
                <div class="example-alert mb-2">
                    <div class="alert alert-fill alert-success alert-icon">
                        <em class="icon ni ni-check-circle"></em> <strong>Coool!</strong>. {{ session('success') }} </div>
                </div>
                @endif

                @if (session('error'))
                <div class="example-alert mb-2">
                    <div class="alert alert-fill alert-danger alert-icon">
                        <em class="icon ni ni-cross-circle"></em> <strong>Oooops</strong>! {{ session('error') }} </div>
                </div>
                @endif

                <div class="row g-gs">
                    <div class="col-lg-12">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="bg-gray-300">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Article Title</th>
                                                <th scope="col">Article Slug</th>
                                                <th scope="col">Article Content</th>
                                                <th scope="col">Article Thumbnail</th>
                                                <th scope="col">Article Category</th>
                                                <th scope="col">Article Tag</th>
                                                <th scope="col">Author</th>
                                                <th scope="col">Article Status</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Action</th>
                                            </tr><!-- .nk-tb-item -->
                                        </thead>
                                        <tbody>
                                            @forelse ($articles as $key => $article)
                                                <tr>
                                                    <td>{{ $articles->firstItem() + $key }}</td>
                                                    <td>{{ $article->article_title }}</td>
                                                    <td>{{ $article->article_slug }}</td>
                                                    <td>{!! Str::limit(strip_tags($article->article_content), $limit = 75, '...') !!}</td>
                                                    <td>
                                                        <img src="{{ $article->takeImage }}" width="250" height="150" alt="{{ $article->article_slug }}">
                                                    </td>
                                                    <td>
                                                        {{ $article->category->category_name }}
                                                    </td>
                                                    <td>
                                                        @foreach ($article->tags as $tag)
                                                            <span class="badge badge-primary">
                                                                {{ $tag->tag_name }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-pill badge-info">{{ $article->author->name }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($article->article_status == 'Publish')
                                                            <span class="badge badge-dot badge-dot-xs badge-success">
                                                                {{ $article->article_status }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-dot badge-dot-xs badge-danger">
                                                                {{ $article->article_status }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $article->created_at->format('d F Y, H:i') }}</td>
                                                    <td>
                                                        <div class="d-block">
                                                            <form action="{{ route('trash.article.restore', $article->article_slug) }}" method="post">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-info mb-1"><em class="icon ni ni-undo"></em><span>Restore</span></button>
                                                            </form>
                                                            <form action="{{ route('trash.article.delete', $article->article_slug) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button class="btn btn-sm btn-danger mb-1 delete-confirm" data-name="{{ $article->article_title }}"><em class="icon ni ni-trash"></em><span>Delete</span></button>
                                                            </form>

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11">
                                                        @if (request('query'))
                                                            <h5 class="text-center">Search result with keyword " {{ request('query') }} " was not found</h5>
                                                        @else
                                                            <h5 class="text-center">Ops, There's no article here!</h5>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table><!-- .nk-tb-list -->
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="d-flex justify-content-center">
                                    {{ $articles->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
         $('.delete-confirm').click(function(event) {
            var form =  $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                title: `Are you sure you want to delete "${name}"?`,
                text: "Article will delete permanent!.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((result) => {
                if (result) {
                form.submit();
                }
            });
        });
    </script>
@endpush
