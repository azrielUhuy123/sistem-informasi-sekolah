<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

    <div
        class="card-body p-4 p-lg-5 text-white"
        style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <h2 class="fw-bold mb-2">
                    {{ $title }}
                </h2>

                <p class="mb-0 opacity-75">
                    {{ $description }}
                </p>

            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">

                {{ $slot }}

            </div>

        </div>

    </div>

</div>