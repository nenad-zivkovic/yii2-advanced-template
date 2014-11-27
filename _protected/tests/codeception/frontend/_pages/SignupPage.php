<?php
namespace tests\codeception\frontend\_pages;

use yii\codeception\BasePage;

/**
 * Represents Signup Page
 */
class SignupPage extends BasePage
{
    public $route = 'site/signup';

    /**
     * Method representing user submitting signup form.
     *
     * @param array $signupData
     */
    public function submit(array $signupData)
    {
        foreach ($signupData as $field => $value) 
        {
            $inputType = $field === 'body' ? 'textarea' : 'input';

            $this->actor->fillField($inputType . '[name="SignupForm[' . $field . ']"]', $value);
        }
        
        $this->actor->click('signup-button');
    }
}
