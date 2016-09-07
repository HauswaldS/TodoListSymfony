var FolderSection = React.createClass({
    
    getInitialState: function() {
        return {
            folders: []
        }
    },

    componentDidMount: function(){
        this.loadFoldersFromServer();
        setInterval(this.loadFoldersFromServer, 2000)
    },

    loadFoldersFromServer: function() {
        $.ajax({
            url : '/dashboard/1/folders',
            success: function(data) {
                this.setState({folders: data});
            }.bind(this)
        });
    },

    render: function() {
        return (
            <div class="jumbotron">
                <FolderBox folders={ this.state.folders } />
            </div>
        );
    }
});

var FolderBox = React.createClass({
    var folderBox = this.props.folders.map(function(folder){
        return (
            <FolderBox username={folders.username} />
        );
    });

    render: function() {
        return (
            <div>
                <h1> {}</h1>
            </div>
        );
    }

});