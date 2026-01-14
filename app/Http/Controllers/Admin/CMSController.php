<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageItem;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CMSController extends Controller
{


    /**
     * Preview page (for admin)
     */
    public function preview(Page $page)
    {
        $page->load(['sections' => function ($q) {
            $q->with(['items' => function ($q) {
                $q->orderBy('sort_order');
            }])
                ->orderBy('sort_order');
        }]);

        return view('frontend.cms.page', compact('page'));
    }
    
    // ============ ADMIN METHODS ============

    /**
     * Admin index page
     */
    public function adminIndex()
    {
        return view('admin.content-management');
    }

    /**
     * Get all pages for admin
     */
    public function getData()
    {
        try {
            $pages = Page::with(['sections' => function ($q) {
                $q->withCount('items');
            }])->orderBy('sort_order')->get();

            return response()->json([
                'status' => 'success',
                'pages' => $pages
            ]);
        } catch (\Exception $e) {
            Log::error('[CMSController] getData() failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pages: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pageStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'hero_text' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'template' => 'nullable|string|in:default,legal,brand,faq',
            'status' => 'required|in:0,1',
            'og_image' => 'nullable|string|max:500',
            'show_in_nav' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $maxOrder = Page::max('sort_order') ?? 0;

            $page = Page::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'hero_text' => $request->hero_text,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'template' => $request->template ?? 'default',
                'status' => $request->status,
                'sort_order' => $request->sort_order ?? $maxOrder + 1,
                'og_image' => $request->og_image,
                'show_in_nav' => $request->show_in_nav ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Page created successfully!',
                'page' => $page->load('sections')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create page: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPage(Page $page)
    {
        try {
            return response()->json([
                'status' => 'success',
                'page' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch page'
            ], 500);
        }
    }

    public function pageUpdate(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'hero_text' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'template' => 'nullable|string|in:default,legal,brand,faq',
            'status' => 'required|in:0,1',
            'show_in_nav' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $page->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'hero_text' => $request->hero_text,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'template' => $request->template ?? 'default',
                'status' => $request->status,
                'og_image' => $request->og_image,
                'sort_order' => $request->sort_order ?? $page->sort_order,
                'show_in_nav' => $request->show_in_nav ?? $page->show_in_nav,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Page updated successfully!',
                'page' => $page->load('sections')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update page: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pageDestroy(Page $page)
    {
        try {
            Log::info("Starting deletion for Page ID: {$page->id}");

            DB::beginTransaction();

            // Delete all related sections and their items
            $page->sections()->each(function ($section) {
                Log::info("Deleting items for Section ID: {$section->id}");
                $section->items()->delete();
                Log::info("Deleting Section ID: {$section->id}");
                $section->delete();
            });

            // Delete the page
            Log::info("Deleting Page ID: {$page->id}");
            $page->delete();

            DB::commit();

            Log::info("Page ID {$page->id} deleted successfully");

            return response()->json([
                'status' => 'success',
                'message' => 'Page deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Failed to delete Page ID: {$page->id}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete page: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePageOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pages' => 'required|array',
            'pages.*.id' => 'required|exists:pages,id',
            'pages.*.sort_order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data'
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->pages as $item) {
                Page::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Page order updated!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order'
            ], 500);
        }
    }

    // ============ SECTIONS ============
    public function getSectionsByPage(Page $page)
    {
        try {
            $sections = $page->sections()
                ->with(['items' => function ($q) {
                    $q->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'status' => 'success',
                'sections' => $sections,
                'page' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load sections'
            ], 500);
        }
    }

    public function sectionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required|exists:pages,id',
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:500',
            'layout_type' => ['required', 'string', Rule::in(array_keys(PageSection::layoutTypes()))],
            'background_theme' => 'nullable|string|in:light,dark,gradient',
            'css_classes' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer', 
            'is_visible' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $maxOrder = PageSection::where('page_id', $request->page_id)->max('sort_order') ?? 0;

            $section = PageSection::create([
                'page_id' => $request->page_id,
                'heading' => $request->heading,
                'subheading' => $request->subheading,
                'layout_type' => $request->layout_type,
                'background_theme' => $request->background_theme ?? 'light',
                'css_classes' => $request->css_classes,
                'is_visible' => $request->is_visible,
                'sort_order' => $request->sort_order ?? $maxOrder + 1,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Section created successfully!',
                'section' => $section->load('items')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create section: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSection(PageSection $section)
    {
        try {
            return response()->json([
                'status' => 'success',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch section'
            ], 500);
        }
    }

    public function sectionUpdate(Request $request, PageSection $section)
    {
        $validator = Validator::make($request->all(), [
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:500',
            'layout_type' => ['required', 'string', Rule::in(array_keys(array: PageSection::layoutTypes()))],
            'background_theme' => 'nullable|string|in:light,dark,gradient',
            'css_classes' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer', 
            'is_visible' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $section->update([
                'heading' => $request->heading,
                'subheading' => $request->subheading,
                'layout_type' => $request->layout_type,
                'background_theme' => $request->background_theme ?? $section->background_theme,
                'css_classes' => $request->css_classes,
                'sort_order' => $request->sort_order ?? $section->sort_order,
                'is_visible' => $request->is_visible,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Section updated successfully!',
                'section' => $section->load('items')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update section: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSectionOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.sort_order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data'
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->sections as $item) {
                PageSection::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Section order updated!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order'
            ], 500);
        }
    }

    public function sectionDestroy(PageSection $section)
    {
        try {
            DB::beginTransaction();

            $section->items()->delete();
            $section->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Section deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete section: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============ ITEMS ============
    public function getItemsBySection(PageSection $section)
    {
        try {
            $items = $section->items()->orderBy('sort_order')->get();
            return response()->json([
                'status' => 'success',
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load items'
            ], 500);
        }
    }

    public function itemStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:page_sections,id',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|string|max:500',
            'cta_label' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:500',
            'width' => 'nullable|string|in:full,half,third,quarter',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $maxOrder = PageItem::where('section_id', $request->section_id)->max('sort_order') ?? 0;

            $item = PageItem::create([
                'section_id' => $request->section_id,
                'title' => $request->title,
                'content' => $request->content,
                'icon' => $request->icon,
                'image_url' => $request->image_url,
                'cta_label' => $request->cta_label,
                'cta_link' => $request->cta_link,
                'width' => $request->width ?? 'full',
                'sort_order' => $request->sort_order ?? $maxOrder + 1,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item created successfully!',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getItem(PageItem $item)
    {
        try {
            return response()->json([
                'status' => 'success',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch item'
            ], 500);
        }
    }

    public function itemUpdate(Request $request, PageItem $item)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|string|max:500',
            'cta_label' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:500',
            'width' => 'nullable|string|in:full,half,third,quarter',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $item->update([
                'title' => $request->title,
                'content' => $request->content,
                'icon' => $request->icon,
                'image_url' => $request->image_url,
                'cta_label' => $request->cta_label,
                'cta_link' => $request->cta_link,
                'width' => $request->width ?? $item->width,
                'sort_order' => $request->sort_order ?? $item->sort_order,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item updated successfully!',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateItemOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:page_items,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data'
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                PageItem::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item order updated!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order'
            ], 500);
        }
    }

    public function itemDestroy(PageItem $item)
    {
        try {
            DB::beginTransaction();

            $item->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete item: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============ MEDIA UPLOAD ============
    public function uploadMedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'type' => 'required|in:item,section,og',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = $request->file('image')->store('cms/' . $request->type, 'public');
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'status' => 'success',
                'url' => $url,
                'path' => $path,
                'message' => 'Image uploaded successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
}
