<?Php
namespace App\Http\Controllers\Admin\Contact;

use App\Models\ContactInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ContactInfoController extends Controller
{
    // Show all contact info
 
public function index()
{
    $infos = Cache::remember('contact_infos', 3600, fn() => ContactInfo::all());

    return view('admin.contact.contact-info', compact('infos'));
}

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'label' => 'required|string',
            'value' => 'required|string',
            'icon' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);

        ContactInfo::create($request->all());

        return redirect()->back()->with('success', 'Contact info added successfully!');
    }

    // Update  contact info
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'label' => 'required|string',
            'value' => 'required|string',
            'icon' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);

        $info = ContactInfo::findOrFail($id);
        $info->update($request->all());

        return redirect()->back()->with('success', 'Contact info updated successfully!');
    }

    // Delete contact info
    public function destroy($id)
    {
        $info = ContactInfo::findOrFail($id);
        $info->delete();

        return redirect()->back()->with('success', 'Contact info deleted successfully!');
    }
}
