$(document).ready(function(){
    $('#create-root-btn').click(function(){
        createTreeItemChild(0);
    });

    let timerId = 0;
    $(document).on('click', '.item_btn', function() {
        let item_id = parseInt($(this).parent('li.tree_item').attr('item_id'));
        if ($(this).hasClass('add_child'))
        {
            createTreeItemChild(item_id);
        }
        if ($(this).hasClass('remove_child'))
        {
            $('#deleteItemModal').attr('del_item_id', item_id);
            $('#deleteItemModal').modal('show');
            let time_delay = 20;
            timerId = setInterval(function() {
                if (time_delay == 0)
                {
                    clearInterval(timerId);
                    $('#deleteItemModal').modal('hide');
                }
                $('.delete-counter').text(time_delay--);
            }, 1000);
        }
    });

    $('.delete-conf').click(function(){
        subtreeDelete($('#deleteItemModal').attr('del_item_id'));
        $('#deleteItemModal').modal('hide');
        closeDeleteItemModal();
    });

    let deleteItemModal = document.getElementById('deleteItemModal');
    deleteItemModal.addEventListener('hidden.bs.modal', event => {
        closeDeleteItemModal();
    });

    function closeDeleteItemModal()
    {
        $('#deleteItemModal .delete-counter').text('');
        clearInterval(timerId);
    }

    $(document).on('click', '.tree_item .arrow', function(){
        let deg = $(this).hasClass('opened')? -90: 0;
        $(this).find('img').css({transform: 'rotate('+deg+'deg)'});
        if ($(this).hasClass('opened')) $(this).removeClass('opened'); else $(this).addClass('opened');
        $(this).parent('.tree_item').find('>ul.subtree').slideToggle('fast');
    });

    $(document).on('click', '.tree_item .item_name', function(){
        let item_id = $(this).parent('.tree_item').attr('item_id');
        let cur_item_name = $(this).text();
        $('#changeTreeItemNameModal').attr('item_id', item_id);
        $('#new-name').val(cur_item_name);
        $('#changeTreeItemNameModal').modal('show');
    });

    $('.save-item-name-btn').click(function (){
        let item_id = $(this).parents('#changeTreeItemNameModal').attr('item_id');
        let new_name = $('#new-name').val();
        //console.log(item_id+' '+new_name);
        changeTreeItemName(item_id, new_name)
    });
});

function createTreeItemChild(item_id)
{
    $.ajax({
        type: 'POST',
        url: 'ajax/add_tree_item.php',
        data: 'item_id='+item_id,
        success: function(data){
            //console.log(data);
            let container;
            if (item_id)
            {
                let item_el = $('.tree_item[item_id='+item_id+']');
                container = item_el.find('>ul.subtree');
                item_el.find('>.arrow').removeClass('hidden');
            } else {
                container = $('#root-container');
            }
            container.append(data);
        },
        error: function () {
            console.log('ajax error');
        }
    });
}

function subtreeDelete(item_id)
{
    if (item_id == undefined) return;
    item_id = parseInt(item_id);
    $.ajax({
        type: 'POST',
        url: 'ajax/remove_subtree.php',
        data: 'item_id='+item_id,
        success: function(data){
            //console.log(data);
            if (data == '1')
            {
                $('.tree_item[item_id='+item_id+']').remove();
            } else alert('Error at subtree deletion');
        },
        error: function () {
            console.log('ajax error');
        }
    });
}

function changeTreeItemName(item_id, name)
{
    $.ajax({
        type: 'POST',
        url: 'ajax/update_tree_item.php',
        data: 'item_id='+item_id+'&name='+name,
        success: function(data){
            //console.log(data);
            if (data == '1')
            {
                $('.tree_item[item_id='+item_id+']>.item_name').text(name);
                $('#changeTreeItemNameModal').modal('hide');
            } else alert('Error at changing item name');
        },
        error: function () {
            console.log('ajax error');
        }
    });
}