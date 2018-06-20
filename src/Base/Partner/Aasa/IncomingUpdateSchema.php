<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 20.06.18
 * Time: 15:39
 */

namespace App\Base\Partner\Aasa;

use Broker\Domain\Interfaces\Partner\SchemaInterface;

class IncomingUpdateSchema implements SchemaInterface
{
  /**
   * @var array
   */
  protected $schema;

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getSchema()
  {
    return $this->schema;
  }

  public function __construct()
  {
    $this->schema = [
      'type' => 'object',
      'required' => [
        'update'
      ],
      'properties' => [
        'update' => [
          'type' => 'object',
          'required' => [
            'status'
          ],
          'properties' => [
            'id' => [
              'type' => 'string'
            ],
            'status' => [
              'type' => 'string',
              'enum' => [
                'Accepted',
                'InProcess',
                'Rejected',
                'Signed',
                'Active',
                'PaidBack'
              ]
            ],
            'amount' => [
              'type' => 'number'
            ],
            'period' => [
              'type' => 'string'
            ],
            'message' => [
              'type' => 'string'
            ],
            'esignUrl' => [
              'type' => 'string'
            ],
            'acceptancePageUrl' => [
              'type' => 'string'
            ],
            'requiredDocuments' => [
              'type' => 'array'
            ],
            'loanDetails' => [
              'type' => 'object',
              'properties' => [
                'toBankAccount' => [
                  'type' => 'number'
                ],
                'interest' => [
                  'type' => 'number'
                ],
                'arrangementFee' => [
                  'type' => 'string'
                ],
                'administrationFee' => [
                  'type' => 'string'
                ],
                'apr' => [
                  'type' => 'number'
                ],
                'monthly' => [
                  'type' => 'object',
                  'properties' => [
                    'first' => [
                      'type' => 'string'
                    ],
                    'avg' => [
                      'type' => 'number'
                    ]
                  ]
                ]
              ]
            ],
            'signingMethod' => [
              'type' => 'string'
            ]
          ]
        ]
      ]
    ];
  }

  /**
   * Specify data which should be serialized to JSON
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by <b>json_encode</b>,
   * which is a value of any type other than a resource.
   * @since 5.4.0
   */
  public function jsonSerialize()
  {
    return $this->schema;
  }
}