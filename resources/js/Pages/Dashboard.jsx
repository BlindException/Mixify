import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
export default function Dashboard(props) {
const hasAccessToken = props.auth.user.access_token;
const hasRefreshToken = props.auth.user.refresh_token;
return (
<AuthenticatedLayout
auth={props.auth}
errors={props.errors}
header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
>
<Head title="Dashboard" />

<div className="py-12">
<div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div className="p-6 text-gray-900">
{hasAccessToken || hasRefreshToken ? (
<a href="/mixify/">Create new Mixify Mix</a>
) : (
<a href="/auth/spotify">Connect to Spotify</a>
)}
</div>
</div>
</div>
</div>
</AuthenticatedLayout>
);
}