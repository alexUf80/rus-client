<?php

class AccountDocsController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
    	
        $files = array();
        $other_files = array();
        $other_cards = [];
        foreach ($this->users->get_files(array('user_id' => $this->user->id)) as $f)
        {
            if ($f->type == 'file' || $f->type == 'document') {
                $other_files[] = $f;
            } elseif ($f->type == 'other_card') {
                $other_cards[] = $f;
            } else {
                $files[$f->type] = $f;
            }
        }
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($files);echo '</pre><hr />';
        $this->design->assign('files', $files);
        $this->design->assign('other_cards', $other_cards);
        $this->design->assign('other_files', $other_files);

        if ($last_order = $this->orders->get_last_order($this->user->id))
            $documents = $this->documents->get_documents(array('order_id' => $last_order->id, 'client_visible'=>1));
        else
            $documents = $this->documents->get_documents(array('user_id' => $this->user->id, 'client_visible'=>1));
        
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($last_order);echo '</pre><hr />';        
        $this->design->assign('documents', $documents);

        $receipts = $this->Receipts->get_receipts($this->user->id);

        if(!empty($receipts)){
            foreach ($receipts as $receipt){
                $response = json_decode($receipt->response);
                $receipt->title = $response->lines[0]->title;
            }
        }
        $this->design->assign('receipts', $receipts);

        if ($otherCardAdded = $this->session->get('otherCardAdded')) {
            $this->design->assign('otherCardAdded', $otherCardAdded);
        }

        $userIdsForClaims = [102068, 129032, 128375, 125317, 114244, 112614, 103775, 126763, 126354, 101930, 134553, 113231, 106660, 128433, 102457, 130788, 104176, 100676, 130511, 124163, 101722, 128393, 103662, 102545, 106939, 106101, 133232, 128720, 126530, 112377, 117862, 105462, 128396, 133780, 101347, 102275, 102358, 126401, 133714, 102693, 103645, 101712, 103623, 104943, 125837, 118892, 108809, 104476, 114213, 131627, 101940, 105778, 105923, 108929, 129194, 119866, 109020, 106766, 104428, 129116, 117358, 107214, 127724, 128256, 126101, 127743, 109031, 106834, 103937, 113292, 123272, 106772, 100694, 102152, 106300];
        $this->design->assign('userIdsForClaims', $userIdsForClaims);

        $orders = $this->orders->get_orders(['user_id' => $this->user->id]);
        $order_recvery = $this->OrdersRecovery->gets();
        $recovers = 0;

        if(!empty($order_recvery)){
            foreach ($order_recvery as $recovery){
                foreach ($orders as $order){
                    if($order->order_id == $recovery->order_id)
                        $recovers = 1;
                }
            }
        }

        $this->design->assign('recovers', $recovers);

        return $this->design->fetch('account/docs.tpl');
    }
    
}