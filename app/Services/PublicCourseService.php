<?php
namespace App\Services;

use App\Repositories\CourseRepository;

class PublicCourseService
{
    protected $courseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function getCatalog($search)
    {
        return $this->courseRepo->getPublishedCourses($search);
    }

    public function getCourseDetail($slug)
    {
        return $this->courseRepo->findBySlug($slug);
    }
}