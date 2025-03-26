<x-filament::page class="filament-dashboard-page">
    @if (session('success'))
    <div class="alert alert-success success-messages">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger error-messages">
            {{ session('error') }}
        </div>
    @endif
    @php
        //gett the widgets here
        //dont display the user chart if the user is not an admin
        $user = auth()->user();
        $role = $user->getRoleAttribute();

        $widgets = $this->getWidgets();
        // dd($this->getWidgets());
        if ($role !== 'admin') {
            $widgets = array_filter($this->getWidgets(), function($widget) {
                return $widget !== \App\Filament\Widgets\UsersChart::class;
            });
        }
    @endphp
    <x-filament::widgets :widgets="$widgets" :columns="$this->getColumns()" />

        
        {{-- use jquery cdn --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".error-messages").slideUp("slow");
            }, 4000); // 6000 milliseconds = 6 seconds
        });
        $(document).ready(function() {
            setTimeout(function() {
                $(".success-messages").slideUp("slow");
            }, 4000); // 6000 milliseconds = 6 seconds
        });
    </script>

    <style>
        .success-messages {
            background-color: rgb(19, 172, 19);
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .error-messages {
            background-color: red;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</x-filament::page>
