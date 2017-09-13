<?php

namespace Drupal\da_poll\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\da_poll\Entity\PollInterface;

/**
 * Class PollController.
 *
 *  Returns responses for Poll routes.
 *
 * @package Drupal\da_poll\Controller
 */
class PollController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Poll  revision.
   *
   * @param int $da_poll_revision
   *   The Poll  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($da_poll_revision) {
    $da_poll = $this->entityTypeManager()
      ->getStorage('da_poll')
      ->loadRevision($da_poll_revision);
    $view_builder = $this->entityManager()->getViewBuilder('da_poll');

    return $view_builder->view($da_poll);
  }

  /**
   * Page title callback for a Poll  revision.
   *
   * @param int $da_poll_revision
   *   The Poll  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($da_poll_revision) {
    $da_poll = $this->entityManager()
      ->getStorage('da_poll')
      ->loadRevision($da_poll_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $da_poll->label(),
      '%date' => format_date($da_poll->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Poll .
   *
   * @param \Drupal\da_poll\Entity\PollInterface $da_poll
   *   A Poll  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PollInterface $da_poll) {
    $account = $this->currentUser();
    $langcode = $da_poll->language()->getId();
    $langname = $da_poll->language()->getName();
    $languages = $da_poll->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $da_poll_storage = $this->entityManager()->getStorage('da_poll');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $da_poll->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $da_poll->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all poll revisions") || $account->hasPermission('administer poll entities')));
    $delete_permission = (($account->hasPermission("delete all poll revisions") || $account->hasPermission('administer poll entities')));

    $rows = [];

    $vids = $da_poll_storage->revisionIds($da_poll);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\da_poll\PollInterface $revision */
      $revision = $da_poll_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)
          ->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')
          ->format($revision->revision_timestamp->value, 'short');
        if ($vid != $da_poll->getRevisionId()) {
          $link = $this->l($date, new Url('entity.da_poll.revision', [
            'da_poll' => $da_poll->id(),
            'da_poll_revision' => $vid,
          ]));
        }
        else {
          $link = $da_poll->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')
                ->renderPlain($username),
              'message' => [
                '#markup' => $revision->revision_log_message->value,
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ? Url::fromRoute('entity.da_poll.translation_revert', [
                'da_poll' => $da_poll->id(),
                'da_poll_revision' => $vid,
                'langcode' => $langcode,
              ]) : Url::fromRoute('entity.da_poll.revision_revert',
                [
                  'da_poll' => $da_poll->id(),
                  'da_poll_revision' => $vid,
                ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.da_poll.revision_delete', [
                'da_poll' => $da_poll->id(),
                'da_poll_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['da_poll_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }


  /**
   * Returns current election.
   *
   * @return mixed|null
   *   The id of the current election or NULL.
   */
  public function getActiveElectionId() {
    $query = Database::getConnection('default')
      ->select('da_poll_field_data', 'dafd')
      ->fields('dafd', ['id']);
    $query->condition('dafd.type', 'election', '=');
    $query->join('da_poll__field_active_election', 'dpfae', 'dafd.id = dpfae.entity_id');
    $query->join('da_poll__field_election_start_date', 'dpfesd', 'dafd.id = dpfesd.entity_id');
    $query->join('da_poll__field_election_end_date', 'dpfend', 'dafd.id = dpfend.entity_id');
    $query->condition('dpfae.field_active_election_value', 1, '=');
    $query->condition('dpfesd.field_election_start_date_value', time(), '<');
    $query->condition('dpfend.field_election_end_date_value', time(), '>');

    $result = $query->execute()->fetchAll();
    if (empty($result)) {
      return NULL;
    }

    return current($result);
  }

}
