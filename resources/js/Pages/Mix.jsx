import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
export default function Mix(props) {
return (
<AuthenticatedLayout
auth={props.auth}
errors={props.errors}
header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Mix</h2>}
>
<Head title="Mix" />
<div className="py-12">
<div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div className="p-6 text-gray-900">
<ul>
{props.playlists.map((playlist) => (
<li key={playlist.id}>
<input type="checkbox" id={playlist.id} value={playlist.id} name="selectedLists[]"/>
<label htmlFor={playlist.id}>{playlist.name}</label>
</li>
))}
</ul>
</div>
</div>
</div>
</div>
</AuthenticatedLayout>
);
}