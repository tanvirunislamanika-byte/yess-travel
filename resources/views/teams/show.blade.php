<x-admin-layout>
    <div class="container-xl px-4">

        <div class="card mb-4">
            <livewire:admin.common.header :title="'Team Settings'" :content="'List of Team Members are below'" :icon="'fa-school'" :term="'Team Settings'" :slug="''" :button="__(' Team Members')"/>
            <div class="card-body">
                
               <div class="tab-content" id="nav-tabContent">

 
        <div class="">
            @livewire('teams.update-team-name-form', ['team' => $team])

            @livewire('teams.team-member-manager', ['team' => $team])

            @if (Gate::check('delete', $team) && ! $team->personal_team)
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('teams.delete-team-form', ['team' => $team])
                </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
   
</x-admin-layout>
