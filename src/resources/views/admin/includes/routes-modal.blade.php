<button type="button"
        class="btn btn-primary btn-sm mt-2"
        data-bs-toggle="modal"
        data-bs-target="#routesModal">
    Список путей
</button>

<div class="modal fade bd-example-modal-lg"
     id="routesModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="routesModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Routes list</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    @foreach($routes as $name => $uri)
                        <li>
                            <span class="text-success">{{ $name }}</span> => {{ $uri }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>