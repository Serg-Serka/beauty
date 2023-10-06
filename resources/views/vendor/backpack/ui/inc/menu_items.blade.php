{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Salons" icon="la la-question" :link="backpack_url('salon')" />
<x-backpack::menu-item title="Services" icon="la la-question" :link="backpack_url('service')" />
<x-backpack::menu-item title="Appointments" icon="la la-question" :link="backpack_url('appointment')" />
<x-backpack::menu-item title="Service types" icon="la la-question" :link="backpack_url('service-type')" />