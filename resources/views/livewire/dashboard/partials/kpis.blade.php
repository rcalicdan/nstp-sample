<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
    <x-ui.stat-card 
        title="Total Trainees" 
        :value="number_format($this->kpis['total_students'])" 
        bg="bg-[#2d0012]" 
        textColor="text-[#f9c22e]" 
    />
    <x-ui.stat-card 
        title="CWTS Enrollees" 
        :value="number_format($this->kpis['total_cwts'])" 
        bg="bg-[#4a001c]" 
        textColor="text-white" 
    />
    <x-ui.stat-card 
        title="ROTC Cadets" 
        :value="number_format($this->kpis['total_rotc'])" 
        bg="bg-[#4a001c]" 
        textColor="text-white" 
    />
    <x-ui.stat-card 
        title="LTS Volunteers" 
        :value="number_format($this->kpis['total_lts'])" 
        bg="bg-[#660028]" 
        textColor="text-[#fde68a]" 
    />
</div>