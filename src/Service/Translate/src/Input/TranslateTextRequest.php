<?php

namespace AsyncAws\Translate\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Translate\Enum\Formality;
use AsyncAws\Translate\ValueObject\TranslationSettings;

final class TranslateTextRequest extends Input
{
    /**
     * The text to translate. The text string can be a maximum of 5,000 bytes long. Depending on your character set, this
     * may be fewer than 5,000 characters.
     *
     * @required
     *
     * @var string|null
     */
    private $text;

    /**
     * The name of the terminology list file to be used in the TranslateText request. You can use 1 terminology list at most
     * in a `TranslateText` request. Terminology lists can contain a maximum of 256 terms.
     *
     * @var string[]|null
     */
    private $terminologyNames;

    /**
     * The language code for the language of the source text. The language must be a language supported by Amazon Translate.
     * For a list of language codes, see Supported languages.
     *
     * @see https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
     * @required
     *
     * @var string|null
     */
    private $sourceLanguageCode;

    /**
     * The language code requested for the language of the target text. The language must be a language supported by Amazon
     * Translate.
     *
     * @required
     *
     * @var string|null
     */
    private $targetLanguageCode;

    /**
     * Settings to configure your translation output, including the option to set the formality level of the output text and
     * the option to mask profane words and phrases.
     *
     * @var TranslationSettings|null
     */
    private $settings;

    /**
     * @param array{
     *   Text?: string,
     *   TerminologyNames?: string[],
     *   SourceLanguageCode?: string,
     *   TargetLanguageCode?: string,
     *   Settings?: TranslationSettings|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->text = $input['Text'] ?? null;
        $this->terminologyNames = $input['TerminologyNames'] ?? null;
        $this->sourceLanguageCode = $input['SourceLanguageCode'] ?? null;
        $this->targetLanguageCode = $input['TargetLanguageCode'] ?? null;
        $this->settings = isset($input['Settings']) ? TranslationSettings::create($input['Settings']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSettings(): ?TranslationSettings
    {
        return $this->settings;
    }

    public function getSourceLanguageCode(): ?string
    {
        return $this->sourceLanguageCode;
    }

    public function getTargetLanguageCode(): ?string
    {
        return $this->targetLanguageCode;
    }

    /**
     * @return string[]
     */
    public function getTerminologyNames(): array
    {
        return $this->terminologyNames ?? [];
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSShineFrontendService_20170701.TranslateText',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setSettings(?TranslationSettings $value): self
    {
        $this->settings = $value;

        return $this;
    }

    public function setSourceLanguageCode(?string $value): self
    {
        $this->sourceLanguageCode = $value;

        return $this;
    }

    public function setTargetLanguageCode(?string $value): self
    {
        $this->targetLanguageCode = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setTerminologyNames(array $value): self
    {
        $this->terminologyNames = $value;

        return $this;
    }

    public function setText(?string $value): self
    {
        $this->text = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->text) {
            throw new InvalidArgument(sprintf('Missing parameter "Text" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Text'] = $v;
        if (null !== $v = $this->terminologyNames) {
            $index = -1;
            $payload['TerminologyNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['TerminologyNames'][$index] = $listValue;
            }
        }
        if (null === $v = $this->sourceLanguageCode) {
            throw new InvalidArgument(sprintf('Missing parameter "SourceLanguageCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SourceLanguageCode'] = $v;
        if (null === $v = $this->targetLanguageCode) {
            throw new InvalidArgument(sprintf('Missing parameter "TargetLanguageCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetLanguageCode'] = $v;
        if (null !== $v = $this->settings) {
            $payload['Settings'] = $v->requestBody();
        }

        return $payload;
    }
}
