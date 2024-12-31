<?php

require_once './app/models/homeModel.php';

class BannerController
{
    private $homeModel;

    public function __construct()
    {
        $this->homeModel = new HomeModel();
    }

    // Fetch banners and display them to the view
    public function getBanners(): array
    {
        return $this->homeModel->getBanners(); // Get banners from the model
    }

    // Fetch a single banner image (if needed for the view)
    public function getCurrentBannerIndex($currentIndex): int
    {
        $banners = $this->getBanners();
        $currentIndex = isset($_GET['banner']) ? (int)$_GET['banner'] : $currentIndex;

        // Ensure the current index is valid
        return max(0, min($currentIndex, count($banners) - 1));
    }

    // Pass the banners to the view
    public function renderBanners()
    {
        $banners = $this->getBanners();
        // Assuming you will pass $banners to the view file (HTML)
        include './app/views/bannerView.php'; // Update the view to display banners
    }
}
