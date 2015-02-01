<?php

class ModelTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGetCategoryByName()
	{
        $categoryRepository = new \EModel\Category\CategoryRepository(new \EModel\Category\Category());
        $modelArr = $categoryRepository->getCategoriesTree(17);

		$this->assertEquals(  'Muc tin', $modelArr);
	}

}
