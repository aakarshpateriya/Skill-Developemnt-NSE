<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = User::where('role', 'admin')->first();

        $courses = [
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript. Perfect for beginners who want to start their journey in web development.',
                'skill_level' => 'Beginner',
                'category' => 'Computer Science',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Advanced Laravel Development',
                'description' => 'Master Laravel framework with advanced concepts like authentication, authorization, API development, and deployment strategies.',
                'skill_level' => 'Advanced',
                'category' => 'Computer Science',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Data Science with Python',
                'description' => 'Learn data analysis, visualization, and machine learning using Python. Perfect for those interested in data science and analytics.',
                'skill_level' => 'Intermediate',
                'category' => 'Computer Science',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Digital Marketing Masterclass',
                'description' => 'Comprehensive course covering SEO, social media marketing, content marketing, and digital advertising strategies.',
                'skill_level' => 'Intermediate',
                'category' => 'Marketing',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'UI/UX Design Principles',
                'description' => 'Learn the fundamentals of user interface and user experience design. Create beautiful and functional digital products.',
                'skill_level' => 'Beginner',
                'category' => 'Design',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Business Analytics',
                'description' => 'Learn how to analyze business data, create reports, and make data-driven decisions for your organization.',
                'skill_level' => 'Intermediate',
                'category' => 'Business',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Mobile App Development with Flutter',
                'description' => 'Build cross-platform mobile applications using Flutter framework. Learn to create beautiful and performant mobile apps.',
                'skill_level' => 'Intermediate',
                'category' => 'Computer Science',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Project Management Professional',
                'description' => 'Master project management methodologies, tools, and best practices. Prepare for PMP certification.',
                'skill_level' => 'Advanced',
                'category' => 'Business',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Graphic Design Fundamentals',
                'description' => 'Learn the principles of graphic design, typography, color theory, and layout design using industry-standard tools.',
                'skill_level' => 'Beginner',
                'category' => 'Design',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ],
            [
                'title' => 'Social Media Marketing Strategy',
                'description' => 'Develop effective social media marketing strategies, create engaging content, and grow your social media presence.',
                'skill_level' => 'Beginner',
                'category' => 'Marketing',
                'instructor_id' => $instructor->id,
                'is_published' => true
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
